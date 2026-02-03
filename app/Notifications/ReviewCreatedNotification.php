<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewCreatedNotification extends Notification
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
        $user = $this->review->user;
        $rating = $this->review->rating;
        $commentExcerpt = $this->review->comment ? \Illuminate\Support\Str::limit($this->review->comment, 100) : 'No comment provided';

        return (new MailMessage)
            ->subject('New Book Review Pending Moderation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new book review has been submitted and is pending moderation.')
            ->line('')
            ->line('**Book Details:**')
            ->line('Title: ' . $book->name)
            ->line('ISBN: ' . $book->isbn)
            ->line('')
            ->line('**Review Details:**')
            ->line('Rating: ' . $rating . '/5 ⭐')
            ->line('Comment: ' . $commentExcerpt)
            ->line('Status: Suspended (Awaiting Moderation)')
            ->line('')
            ->line('**User Information:**')
            ->line('Name: ' . $user->name)
            ->line('Email: ' . $user->email)
            ->line('Reviewed at: ' . $this->review->created_at->format('Y-m-d H:i'))
            ->action('Review and Moderate', route('reviews.show', $this->review))
            ->line('Please review this submission and approve or reject it.')
            ->line('Thank you for managing our library system!');
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
            'user_id' => $this->review->user_id,
            'user_name' => $this->review->user->name,
            'rating' => $this->review->rating,
            'created_at' => $this->review->created_at,
        ];
    }
}
