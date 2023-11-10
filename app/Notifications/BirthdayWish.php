<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BirthdayWish extends Notification
{
    use Queueable;
    private $message;
    private $name;


    public function __construct($message, $name)
    {
        $this->message = $message;
        $this->name = $name;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Login')
            ->line($this->message)
            ->action('Click Here', url('/'))
            ->line('Thank You ');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
