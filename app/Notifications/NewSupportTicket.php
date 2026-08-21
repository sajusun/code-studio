<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSupportTicket extends Notification
{
    use Queueable;

    protected $conversation;

    public function __construct($conversation)
    {
        $this->conversation = $conversation;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $sender = $this->conversation->customer_name ?: $this->conversation->customer_email;
        $subject = $this->conversation->subject ?: 'No Subject';

        return [
            'type' => 'support',
            'target_id' => $this->conversation->id,
            'title' => 'New Support Ticket',
            'message' => "Ticket #{$this->conversation->id}: \"{$subject}\" from {$sender}",
        ];
    }
}
