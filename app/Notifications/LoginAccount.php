<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginAccount extends Notification
{
    use Queueable;
    private $message;
    private $device;
    private $time;


    public function __construct($message, $device, $time)
    {
        $this->message = $message;
        $this->device = $device;
        $this->time = $time;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Security Alert...!')
            ->line($this->message)
            ->line('Device : ' . $this->device)
            ->line('Time : ' . $this->time)
            ->line('Location :Chomu ,Jaipur')
            ->action('Yes! I am', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
