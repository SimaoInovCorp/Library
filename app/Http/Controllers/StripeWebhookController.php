<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Orders\OrderService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    protected $orderService;
    protected $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Stripe webhook
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $this->paymentService->handleWebhook($payload, $signature);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event: ' . $event->type);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSucceeded($paymentIntent)
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();

        if (!$order) {
            Log::warning('Order not found for payment intent: ' . $paymentIntent->id);
            return;
        }

        if ($order->isPaid()) {
            Log::info('Order already marked as paid: ' . $order->id);
            return;
        }

        try {
            $this->orderService->markAsPaid($order, $paymentIntent->id);
            Log::info('Order marked as paid: ' . $order->id);
        } catch (\Exception $e) {
            Log::error('Error marking order as paid: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailed($paymentIntent)
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();

        if (!$order) {
            Log::warning('Order not found for payment intent: ' . $paymentIntent->id);
            return;
        }

        if ($order->status !== Order::STATUS_PENDING) {
            Log::info('Order already processed: ' . $order->id);
            return;
        }

        try {
            $this->orderService->markAsFailed($order);
            Log::info('Order marked as failed: ' . $order->id);
        } catch (\Exception $e) {
            Log::error('Error marking order as failed: ' . $e->getMessage());
        }
    }
}
