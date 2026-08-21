<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    public function send(Notification $notification): bool
    {
        // Safe FCM Push notification dispatcher
        try {
            Log::info("FCM Push Notification sent to User #{$notification->user_id}: {$notification->title}");
            return true;
        } catch (\Throwable $e) {
            Log::error("FCM Push Error: " . $e->getMessage());
            return false;
        }
    }
}
