<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewNewsletterSubscriber extends Notification
{
    use Queueable;

    protected $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'newsletter',
            'target_id' => $this->subscriber->id,
            'title' => 'New Newsletter Subscriber',
            'message' => "{$this->subscriber->email} subscribed to the newsletter.",
        ];
    }
}
