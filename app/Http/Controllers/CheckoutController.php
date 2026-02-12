<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartService;
use App\Services\Orders\OrderService;
use App\Services\Payment\PaymentService;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;
    protected $paymentService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->is_admin) {
                return redirect()->route('home')->with('error', 'Admins cannot make purchases.');
            }
            return $next($request);
        });

        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    /**
     * Step 1: Review cart items
     */
    public function step1()
    {
        $cart = $this->cartService->getOrCreateCart(Auth::id());
        $cart->load(['items.book.authors', 'items.book.publisher']);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate availability
        $unavailable = $this->cartService->validateCartAvailability($cart);
        if (!empty($unavailable)) {
            return redirect()->route('cart.index')->with('error', 'Some items in your cart are no longer available in the requested quantity.');
        }

        $totals = $this->cartService->calculateTotals($cart);

        return view('checkout.step1', compact('cart', 'totals'));
    }

    /**
     * Step 2: Delivery address (GET)
     */
    public function step2()
    {
        $cart = $this->cartService->getOrCreateCart(Auth::id());

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();
        $addresses = $user->addresses;
        $defaultAddress = $user->defaultAddress;

        return view('checkout.step2', compact('cart', 'addresses', 'defaultAddress'));
    }

    /**
     * Step 2: Save delivery address (POST)
     */
    public function saveAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'nullable|exists:user_addresses,id',
            'name' => 'required_without:address_id|string|max:255',
            'address_line1' => 'required_without:address_id|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required_without:address_id|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'required_without:address_id|string|max:20',
            'country' => 'required_without:address_id|string|max:255',
            'phone' => 'required_without:address_id|string|max:20',
            'save_address' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $cart = $this->cartService->getOrCreateCart($user->id);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Get address data
        if ($request->address_id) {
            $address = UserAddress::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $addressData = [
                'name' => $address->name,
                'address_line1' => $address->address_line1,
                'address_line2' => $address->address_line2,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
                'phone' => $address->phone,
            ];
        } else {
            $addressData = $request->only([
                'name', 'address_line1', 'address_line2',
                'city', 'state', 'postal_code', 'country', 'phone'
            ]);

            // Optionally save the address
            if ($request->save_address) {
                UserAddress::create([
                    'user_id' => $user->id,
                    ...$addressData,
                    'is_default' => $user->addresses()->count() === 0,
                ]);
            }
        }

        try {
            // Create the order
            $order = $this->orderService->createFromCart($cart, $addressData);

            // Redirect to payment step
            return redirect()->route('checkout.step3', $order->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Step 3: Payment
     */
    public function step3($orderId)
    {
        $order = Auth::user()->orders()->with(['items.book'])->findOrFail($orderId);

        if (!$order->isPending()) {
            return redirect()->route('orders.show', $order->id)
                ->with('info', 'This order has already been processed.');
        }

        try {
            // Create or retrieve payment intent
            if (!$order->payment_intent_id) {
                $paymentIntent = $this->paymentService->createPaymentIntent($order);
            } else {
                $paymentIntent = $this->paymentService->retrievePaymentIntent($order->payment_intent_id);
            }

            return view('checkout.step3', compact('order', 'paymentIntent'));
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Process payment confirmation
     */
    public function processPayment(Request $request, $orderId)
    {
        $order = Auth::user()->orders()->findOrFail($orderId);

        if (!$order->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Order already processed.'
            ], 400);
        }

        try {
            $paymentIntent = $this->paymentService->retrievePaymentIntent($order->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $this->orderService->markAsPaid($order, $paymentIntent->id);

                return response()->json([
                    'success' => true,
                    'redirect_url' => route('orders.show', $order->id)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment not completed. Status: ' . $paymentIntent->status
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
