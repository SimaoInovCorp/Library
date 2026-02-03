<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RequisitionApprovedNotification extends Notification
{
    public Book $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Book Loan Request Approved: ' . $this->book->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your loan request for the book "' . $this->book->name . '" has been approved.')
            ->action('View Book', url(route('books.show', $this->book)))
            ->line('Thank you for using our library!');
    }

    public function toArray($notifiable)
    {
        return [
            'book_id' => $this->book->id,
            'book_name' => $this->book->name,
            'message' => 'Your loan request for "' . $this->book->name . '" has been approved.'
        ];
    }
}
