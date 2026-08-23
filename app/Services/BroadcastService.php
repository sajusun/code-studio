<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NotificationCreated;
use App\Events\NotificationSent;

class BroadcastService
{
    public function send(Notification $notification): void
    {
        broadcast(new NotificationCreated($notification))->toOthers();
        broadcast(new NotificationSent($notification));
    }
}
