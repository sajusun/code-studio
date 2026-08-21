<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Fetch notifications from database
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get();

        // Seed initial notifications if database is empty for current user
        if ($notifications->isEmpty()) {
            $this->seedInitialNotifications($user->id);
            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->limit(50)
                ->get();
        }

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type ?? 'general',
                'title' => $n->title,
                'desc' => $n->body,
                'time' => $n->created_at ? $n->created_at->diffForHumans() : 'Just now',
                'read' => $n->read_at !== null,
                'color' => match($n->type) {
                    'user' => 'bg-emerald-500/10 text-emerald-500',
                    'card', 'payment' => 'bg-indigo-500/10 text-indigo-500',
                    'security', 'shield' => 'bg-rose-500/10 text-rose-500',
                    default => 'bg-emerald-500/10 text-emerald-500',
                },
                'icon' => match($n->type) {
                    'user' => 'user',
                    'card', 'payment' => 'card',
                    'security', 'shield' => 'shield',
                    default => 'bell',
                }
            ]),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function destroyAll(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)->delete();

        return response()->json(['success' => true]);
    }

    private function seedInitialNotifications($userId): void
    {
        Notification::create([
            'user_id' => $userId,
            'type' => 'user',
            'title' => 'New User Registered',
            'body' => 'John Doe created a new administrative account',
            'created_at' => now()->subMinutes(5),
        ]);

        Notification::create([
            'user_id' => $userId,
            'type' => 'card',
            'title' => 'Payment Received',
            'body' => 'Stripe transaction #89A2BF19 ($250.00)',
            'created_at' => now()->subHour(),
        ]);

        Notification::create([
            'user_id' => $userId,
            'type' => 'shield',
            'title' => 'System Security Alert',
            'body' => 'Successful admin sign in from current browser',
            'read_at' => now()->subHours(2),
            'created_at' => now()->subHours(3),
        ]);
    }
}
