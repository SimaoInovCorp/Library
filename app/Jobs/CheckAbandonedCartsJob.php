<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Models\Setting;
use App\Notifications\AbandonedCartNotification;
use Illuminate\Support\Facades\Log;

class CheckAbandonedCartsJob
{

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $abandonedHours = Setting::get('abandoned_cart_hours', 1);
        $threshold = now()->subHours($abandonedHours);

        // Find carts that:
        // 1. Have items
        // 2. Last activity was before threshold
        // 3. Have not been notified OR were notified more than 24 hours ago (don't spam)
        $abandonedCarts = Cart::with(['user', 'items.book'])
            ->whereHas('items')
            ->where('last_activity_at', '<=', $threshold)
            ->where(function ($query) {
                $query->whereNull('notified_at')
                    ->orWhere('notified_at', '<=', now()->subHours(24));
            })
            ->get();

        Log::info('Checking abandoned carts', [
            'threshold' => $threshold,
            'found' => $abandonedCarts->count()
        ]);

        foreach ($abandonedCarts as $cart) {
            try {
                // Send notification
                $cart->user->notify(new AbandonedCartNotification($cart));

                // Mark as notified
                $cart->update(['notified_at' => now()]);

                Log::info('Sent abandoned cart notification', [
                    'user_id' => $cart->user_id,
                    'cart_id' => $cart->id
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send abandoned cart notification', [
                    'user_id' => $cart->user_id,
                    'cart_id' => $cart->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
