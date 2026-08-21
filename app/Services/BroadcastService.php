<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class BroadcastService
{
    public function send(Notification $notification): bool
    {
        try {
            Log::info("WebSockets Broadcast Event fired for User #{$notification->user_id}: {$notification->title}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Broadcast Error: " . $e->getMessage());
            return false;
        }
    }
}
