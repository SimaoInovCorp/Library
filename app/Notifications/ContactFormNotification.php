<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormNotification extends Notification
{
    // contactData array to hold form submission details
    protected array $contactData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
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
        return (new MailMessage)
            ->subject('New Contact Form Submission - Biblioteca')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new message from the contact form.')
            ->line('')
            ->line('**Contact Details:**')
            ->line('Name: ' . $this->contactData['name'])
            ->line('Email: ' . $this->contactData['email'])
            ->line('')
            ->line('**Message:**')
            ->line($this->contactData['message'])
            ->line('')
            ->line('Please respond to this inquiry at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->contactData['name'],
            'email' => $this->contactData['email'],
            'message' => $this->contactData['message'],
            'type' => 'contact_form',
        ];
    }
}
