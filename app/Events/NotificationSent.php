<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->notification->user_id),
            new Channel('notifications'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'type' => $this->notification->type ?? 'general',
            'title' => $this->notification->title,
            'desc' => $this->notification->body,
            'time' => 'Just now',
            'read' => false,
            'color' => match($this->notification->type) {
                'user' => 'bg-emerald-500/10 text-emerald-500',
                'card', 'payment' => 'bg-indigo-500/10 text-indigo-500',
                'security', 'shield' => 'bg-rose-500/10 text-rose-500',
                default => 'bg-emerald-500/10 text-emerald-500',
            },
            'icon' => match($this->notification->type) {
                'user' => 'user',
                'card', 'payment' => 'card',
                'security', 'shield' => 'shield',
                default => 'bell',
            }
        ];
    }
}
