<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Get or create a cart for the given user
     */
    public function getOrCreateCart($userId)
    {
        $cart = Cart::with(['items.book'])->firstOrCreate(
            ['user_id' => $userId],
            ['last_activity_at' => now()]
        );

        return $cart;
    }

    /**
     * Add an item to the cart
     */
    public function addItem($userId, $bookId, $quantity = 1)
    {
        $book = Book::findOrFail($bookId);

        // Check if book is available
        if ($book->copies < 1) {
            throw new \Exception('This book is currently out of stock.');
        }

        // Check quantity limits
        $maxQuantity = Setting::get('max_cart_quantity_per_book', 10);
        if ($quantity > $maxQuantity) {
            throw new \Exception("Maximum quantity per book is {$maxQuantity}.");
        }

        if ($quantity > $book->copies) {
            throw new \Exception("Only {$book->copies} copies available.");
        }

        $cart = $this->getOrCreateCart($userId);

        return DB::transaction(function () use ($cart, $book, $quantity, $maxQuantity) {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('book_id', $book->id)
                ->first();

            if ($cartItem) {
                // Update existing item
                $newQuantity = $cartItem->quantity + $quantity;

                if ($newQuantity > $maxQuantity) {
                    throw new \Exception("Maximum quantity per book is {$maxQuantity}.");
                }

                if ($newQuantity > $book->copies) {
                    throw new \Exception("Only {$book->copies} copies available.");
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                // Create new item
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'book_id' => $book->id,
                    'quantity' => $quantity,
                    'price' => $book->price,
                ]);
            }

            $cart->touch();
            return $cartItem;
        });
    }

    /**
     * Update item quantity
     */
    public function updateQuantity($cartItemId, $quantity, $userId)
    {
        $cartItem = CartItem::with(['cart', 'book'])->findOrFail($cartItemId);

        // Verify ownership
        if ($cartItem->cart->user_id !== $userId) {
            throw new \Exception('Unauthorized.');
        }

        // Validate quantity
        $maxQuantity = Setting::get('max_cart_quantity_per_book', 10);
        if ($quantity > $maxQuantity) {
            throw new \Exception("Maximum quantity per book is {$maxQuantity}.");
        }

        if ($quantity > $cartItem->book->copies) {
            throw new \Exception("Only {$cartItem->book->copies} copies available.");
        }

        if ($quantity < 1) {
            throw new \Exception('Quantity must be at least 1.');
        }

        $cartItem->update(['quantity' => $quantity]);
        $cartItem->cart->touch();

        return $cartItem;
    }

    /**
     * Remove item from cart
     */
    public function removeItem($cartItemId, $userId)
    {
        $cartItem = CartItem::with('cart')->findOrFail($cartItemId);

        // Verify ownership
        if ($cartItem->cart->user_id !== $userId) {
            throw new \Exception('Unauthorized.');
        }

        $cartItem->delete();
        $cartItem->cart->touch();

        return true;
    }

    /**
     * Clear the entire cart
     */
    public function clearCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->touch();
        }

        return true;
    }

    /**
     * Calculate cart totals
     */
    public function calculateTotals($cart)
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $taxRate = Setting::get('tax_rate', 23.00);
        $tax = $subtotal * ($taxRate / 100);

        $shippingCost = Setting::get('shipping_cost', 5.00);
        $freeShippingThreshold = Setting::get('free_shipping_threshold', 50.00);

        $shipping = $subtotal >= $freeShippingThreshold ? 0 : $shippingCost;

        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'tax_rate' => $taxRate,
            'shipping' => round($shipping, 2),
            'total' => round($total, 2),
            'free_shipping_threshold' => $freeShippingThreshold,
        ];
    }

    /**
     * Validate cart items availability
     */
    public function validateCartAvailability($cart)
    {
        $unavailable = [];

        foreach ($cart->items as $item) {
            if ($item->book->copies < $item->quantity) {
                $unavailable[] = [
                    'book' => $item->book,
                    'requested' => $item->quantity,
                    'available' => $item->book->copies,
                ];
            }
        }

        return $unavailable;
    }
}