<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class UserApprovedNotification extends Notification
{
    use Queueable;

    private $title;
    private $message;
    private $url;

    public function __construct($title, $message, $url = '/')
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class]; // Mengirim via push notification
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->message)
            ->icon(asset('images/spartav_logo.png'))
            ->data([
                'url' => url($this->url),
                'message' => $this->message,
            ]);
    }
}
