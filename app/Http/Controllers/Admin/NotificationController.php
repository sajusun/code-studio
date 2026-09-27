<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Fetch authenticated user's notifications from DB.
     * Returns empty array if none — no auto-seeding.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications->map(fn ($n) => [
                'id'    => $n->id,
                'type'  => $n->type ?? 'general',
                'title' => $n->title,
                'desc'  => $n->body,
                'time'  => $n->created_at ? $n->created_at->diffForHumans() : 'Just now',
                'read'  => $n->read_at !== null,
                'color' => match($n->type) {
                    'user'              => 'bg-emerald-500/10 text-emerald-500',
                    'card', 'payment'   => 'bg-indigo-500/10 text-indigo-500',
                    'security', 'shield'=> 'bg-rose-500/10 text-rose-500',
                    default             => 'bg-emerald-500/10 text-emerald-500',
                },
                'icon'  => match($n->type) {
                    'user'              => 'user',
                    'card', 'payment'   => 'card',
                    'security', 'shield'=> 'shield',
                    default             => 'bell',
                },
            ]),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete ALL notifications for the authenticated user.
     */
    public function destroyAll(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)->delete();

        return response()->json(['success' => true]);
    }
}

