<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->is_admin) {
                return redirect()->route('home')->with('error', 'Admins cannot use the shopping cart.');
            }
            return $next($request);
        });

        $this->cartService = $cartService;
    }

    /**
     * Display the cart
     */
    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(Auth::id());
        $cart->load(['items.book.authors', 'items.book.publisher']);

        $totals = $this->cartService->calculateTotals($cart);

        return view('cart.index', compact('cart', 'totals'));
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'integer|min:1',
        ]);

        try {
            $this->cartService->addItem(
                Auth::id(),
                $request->book_id,
                $request->quantity ?? 1
            );

            return redirect()->back()->with('success', 'Book added to cart!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->updateQuantity($cartItemId, $request->quantity, Auth::id());

            return redirect()->back()->with('success', 'Cart updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy($cartItemId)
    {
        try {
            $this->cartService->removeItem($cartItemId, Auth::id());

            return redirect()->back()->with('success', 'Item removed from cart.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Clear the entire cart
     */
    public function clear()
    {
        try {
            $this->cartService->clearCart(Auth::id());

            return redirect()->back()->with('success', 'Cart cleared.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
