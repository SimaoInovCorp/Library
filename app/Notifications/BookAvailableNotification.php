<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookAvailableNotification extends Notification
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
            ->subject('Book Now Available: ' . $this->book->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The book "' . $this->book->name . '" is now available for request.')
            ->action('Request Book', url(route('books.show', $this->book)))
            ->line('Thank you for using our library!');
    }

    public function toArray($notifiable)
    {
        return [
            'book_id' => $this->book->id,
            'book_name' => $this->book->name,
            'message' => 'The book "' . $this->book->name . '" is now available for request.'
        ];
    }
}
