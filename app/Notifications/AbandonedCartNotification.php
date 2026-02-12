<?php

namespace App\Notifications;

use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbandonedCartNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $cart;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $totalItems = $this->cart->total_items;
        $subtotal = $this->cart->subtotal;

        $message = (new MailMessage)
            ->subject('Complete Your Purchase - Items Waiting in Your Cart')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We noticed you left ' . $totalItems . ' ' . str('item')->plural($totalItems) . ' in your shopping cart.')
            ->line('Your cart total is: €' . number_format($subtotal, 2))
            ->line('')
            ->line('**Items in your cart:**');

        foreach ($this->cart->items as $item) {
            $message->line('- ' . $item->book->name . ' (x' . $item->quantity . ') - €' . number_format($item->total, 2));
        }

        $message->line('')
            ->line('Need help completing your purchase? We\'re here to assist you!')
            ->action('Complete Your Purchase', url(route('cart.index')))
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->line('Thank you for choosing our bookstore!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'cart_id' => $this->cart->id,
            'total_items' => $this->cart->total_items,
            'subtotal' => $this->cart->subtotal,
