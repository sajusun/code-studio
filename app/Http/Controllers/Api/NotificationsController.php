<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request): array
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->limit(50)
            ->get();

        $unreadCount = $user->unreadNotifications()->count();

        return [
            'data' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->data['type'] ?? 'general',
                'target_id' => $n->data['target_id'] ?? null,
                'title' => $n->data['title'] ?? 'Notification',
                'message' => $n->data['message'] ?? '',
                'read_at' => $n->read_at?->toIso8601String(),
                'created_at' => $n->created_at?->toIso8601String(),
            ]),
            'unread_count' => $unreadCount,
        ];
    }

    public function markAsRead(Request $request, string $id): array
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return [
            'success' => true,
        ];
    }

    public function markAllAsRead(Request $request): array
    {
        $request->user()->unreadNotifications->markAsRead();

        return [
            'success' => true,
        ];
    }
}
