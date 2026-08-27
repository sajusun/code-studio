<?php

namespace App\Events;

use App\Models\ProjectMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewProjectMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageData;
    public $projectId;

    public function __construct(ProjectMessage $message)
    {
        $this->projectId = $message->client_project_id;
        $this->messageData = [
            'id'              => $message->id,
            'project_id'      => $message->client_project_id,
            'sender_type'     => $message->sender_type,
            'sender_name'     => $message->sender_name,
            'sender_avatar'   => $message->sender_avatar,
            'message'         => $message->message,
            'attachment_url'  => $message->attachment_url,
            'attachment_name' => $message->attachment_name,
            'created_at'      => $message->created_at?->toIso8601String(),
            'time'            => $message->created_at?->diffForHumans(),
        ];
    }

    public function broadcastOn(): array
    {
        // Public channel for smooth instant client updates without auth friction
        return [
            new Channel('project.' . $this->projectId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'project.message';
    }

    public function broadcastWith(): array
    {
        return $this->messageData;
    }
}
