<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Book;
use App\Services\Cart\CartService;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Create an order from a cart
     */
    public function createFromCart(Cart $cart, array $addressData)
    {
        // Validate cart is not empty
        if ($cart->isEmpty()) {
            throw new \Exception('Cart is empty.');
        }

        // Validate availability
        $unavailable = $this->cartService->validateCartAvailability($cart);
        if (!empty($unavailable)) {
            throw new \Exception('Some items in your cart are no longer available in the requested quantity.');
        }

        // Calculate totals
        $totals = $this->cartService->calculateTotals($cart);

        return DB::transaction(function () use ($cart, $addressData, $totals) {
            // Create order
            $order = Order::create([
                'user_id' => $cart->user_id,
                'cart_id' => $cart->id,
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'tax_rate' => $totals['tax_rate'],
                'shipping' => $totals['shipping'],
                'total' => $totals['total'],
                'currency' => 'EUR',
                'status' => Order::STATUS_PENDING,
                'delivery_address' => $addressData,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cartItem->book_id,
                    'quantity' => $cartItem->quantity,
                    'price_at_purchase' => $cartItem->price,
                    'book_name' => $cartItem->book->name,
                    'book_isbn' => $cartItem->book->isbn,
                ]);
            }

            // Load relationships
            $order->load(['items.book', 'user']);

            return $order;
        });
    }

    /**
     * Mark order as paid and decrement book copies
     */
    public function markAsPaid(Order $order, $paymentIntentId = null)
    {
        return DB::transaction(function () use ($order, $paymentIntentId) {
            // Mark order as paid
            $order->markAsPaid($paymentIntentId);

            // Decrement book copies
            foreach ($order->items as $item) {
                $book = $item->book;
                if ($book) {
                    $book->decrement('copies', $item->quantity);
                }
            }

            // Clear the cart
            if ($order->cart) {
                $order->cart->items()->delete();
            }

            return $order;
        });
    }

    /**
     * Mark order as failed
     */
    public function markAsFailed(Order $order)
    {
        return $order->markAsFailed();
    }

    /**
     * Cancel an order and restore book copies
     */
    public function cancelOrder(Order $order)
    {
        if ($order->isPaid()) {
            return DB::transaction(function () use ($order) {
                // Restore book copies
                foreach ($order->items as $item) {
                    $book = $item->book;
                    if ($book) {
                        $book->increment('copies', $item->quantity);
                    }
                }

                $order->update(['status' => Order::STATUS_CANCELLED]);

                return $order;
            });
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);
        return $order;
    }
}