<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionDueReminderNotification extends Notification
{
    public $requisition;

    /**
     * Create a new notification instance.
     */
    public function __construct($requisition)
    {
        $this->requisition = $requisition;
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
        $book = $this->requisition->book;
        $dueDate = $this->requisition->expected_end_at ? \Carbon\Carbon::parse($this->requisition->expected_end_at)->format('Y-m-d') : '-';
        return (new MailMessage)
            ->subject('Book Return Reminder: ' . ($book->name ?? ''))
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('This is a reminder that your loan for the book "' . ($book->name ?? '-') . '" is due tomorrow (' . $dueDate . ').')
            ->line('Please make sure to return the book on time to avoid any penalties.')
            ->action('View Your Requests', url('/requisitions'))
            ->line('Thank you for using our library!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
