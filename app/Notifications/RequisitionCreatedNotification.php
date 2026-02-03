<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionCreatedNotification extends Notification
{
    protected Requisition $requisition;

    /**
     * Create a new notification instance.
     */
    public function __construct(Requisition $requisition)
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
        $user = $this->requisition->user;
        $authors = $book->authors->pluck('name')->join(', ');

        $message = (new MailMessage)
            ->subject('Book Requisition Confirmation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A book requisition has been created.')
            ->line('**Book Details:**')
            ->line('Title: ' . $book->name)
            ->line('ISBN: ' . $book->isbn)
            ->line('Authors: ' . $authors)
            ->line('Publisher: ' . ($book->publisher->name ?? 'N/A'))
            ->line('')
            ->line('**Requisition Details:**')
            ->line('Requested by: ' . $user->name)
            ->line('Requested at: ' . $this->requisition->requested_at->format('Y-m-d H:i'))
            ->line('Expected end: ' . $this->requisition->expected_end_at->format('Y-m-d H:i'))
            ->line('Status: ' . ucfirst($this->requisition->status))
            ->action('View Requisitions', url('/requisitions'))
            ->line('Thank you for using our library system!');

        // Attach book cover if available
        if ($book->cover_image) {
            $coverPath = storage_path('app/public/' . $book->cover_image);
            if (file_exists($coverPath)) {
                $message->attach($coverPath, [
                    'as' => 'book_cover.jpg',
                    'mime' => 'image/jpeg',
                ]);
            }
        }

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
            'requisition_id' => $this->requisition->id,
            'book_id' => $this->requisition->book_id,
            'user_id' => $this->requisition->user_id,
        ];
    }
}
