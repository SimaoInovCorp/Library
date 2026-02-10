<?php

namespace App\Services\Payment;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Payment Intent for an order
     */
    public function createPaymentIntent(Order $order)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($order->total, $order->currency),
                'currency' => strtolower($order->currency),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                ],
                'description' => "Order #{$order->order_number}",
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $order->update(['payment_intent_id' => $paymentIntent->id]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception('Payment initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a Payment Intent
     */
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve payment: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a Payment Intent (if manual confirmation is needed)
     */
    public function confirmPaymentIntent($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'requires_confirmation') {
                $paymentIntent->confirm();
            }

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            throw new \Exception('Payment confirmation failed: ' . $e->getMessage());
        }
    }

    /**
     * Convert amount to Stripe format (cents)
     */
    protected function convertToStripeAmount($amount, $currency)
    {
        // Most currencies use cents (multiply by 100)
        // Zero-decimal currencies (JPY, KRW, etc.) don't need conversion
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return (int) $amount;
        }

        return (int) ($amount * 100);
    }

    /**
     * Convert Stripe amount to decimal
     */
    public function convertFromStripeAmount($amount, $currency)
    {
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return (float) $amount;
        }

        return (float) ($amount / 100);
    }

    /**
     * Handle Stripe webhook payload
     */
    public function handleWebhook($payload, $signature)
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!$webhookSecret) {
            throw new \Exception('Webhook secret not configured.');
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );

            return $event;
        } catch (\UnexpectedValueException $e) {
            throw new \Exception('Invalid payload: ' . $e->getMessage());
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            throw new \Exception('Invalid signature: ' . $e->getMessage());
        }
    }
}