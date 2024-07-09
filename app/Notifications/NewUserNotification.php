<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $newUser;
    protected $fromAddress;

    public function __construct($newUser, $fromAddress)
    {
        $this->newUser = $newUser;
        $this->fromAddress = $fromAddress;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->from($this->fromAddress, config('app.name'))
                    ->subject('New User Registered')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('A new user has registered.')
                    ->line('Name: ' . $this->newUser->name)
                    ->line('Email: ' . $this->newUser->email)
                    ->action('View User', "")
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'name' => $this->newUser->name,
            'email' => $this->newUser->email,
        ];
    }
}
