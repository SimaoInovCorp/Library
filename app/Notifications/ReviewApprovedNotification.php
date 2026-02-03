<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewApprovedNotification extends Notification
{
    use Queueable;

    protected Review $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $book = $this->review->book;

        return (new MailMessage)
            ->subject('Your Book Review Has Been Approved')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your review for "' . $book->name . '" has been approved and is now visible to other users.')
            ->line('')
            ->line('**Review Details:**')
            ->line('Rating: ' . $this->review->rating . '/5 ⭐')
            ->line('Book: ' . $book->name)
            ->line('ISBN: ' . $book->isbn)
            ->line('')
            ->action('View Book', route('books.show', $book))
            ->line('Thank you for sharing your thoughts with our library community!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'book_id' => $this->review->book_id,
            'book_name' => $this->review->book->name,
            'rating' => $this->review->rating,
            'status' => 'approved',
            'message' => 'Your review for "' . $this->review->book->name . '" has been approved.',
        ];
    }
}
