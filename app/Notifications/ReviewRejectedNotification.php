<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRejectedNotification extends Notification
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
        $message = (new MailMessage)
            ->subject('Your Book Review Has Been Rejected')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We regret to inform you that your review for "' . $book->name . '" has been rejected by our moderation team.')
            ->line('')
            ->line('**Review Details:**')
            ->line('Rating: ' . $this->review->rating . '/5 ⭐')
            ->line('Book: ' . $book->name)
            ->line('ISBN: ' . $book->isbn);

        // Add rejection reason if provided
        if ($this->review->rejection_reason) {
            $message->line('')
                ->line('**Rejection Reason:**')
                ->line($this->review->rejection_reason);
        }

        $message->line('')
            ->action('View Book', route('books.show', $book))
            ->line('If you have any questions, please contact our library administration.')
            ->line('Thank you for your understanding.');

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
            'review_id' => $this->review->id,
            'book_id' => $this->review->book_id,
            'book_name' => $this->review->book->name,
            'rating' => $this->review->rating,
            'status' => 'rejected',
            'rejection_reason' => $this->review->rejection_reason,
            'message' => 'Your review for "' . $this->review->book->name . '" has been rejected.',
        ];
    }
}
