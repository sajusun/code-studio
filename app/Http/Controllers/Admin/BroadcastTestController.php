<?php

namespace App\Http\Controllers\Admin;

use App\Events\NotificationSent;
use App\Events\TestBroadcastEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class BroadcastTestController extends Controller
{
    /**
     * Show the broadcast test dashboard page.
     */
    public function index()
    {
        return view('admin.broadcast-test.index');
    }

    /**
     * Fire a public channel test event (TestBroadcastEvent).
     * No authentication required on the channel — useful for verifying Reverb connection.
     */
    public function testPublicBroadcast(Request $request)
    {
        $message = $request->input('message', 'Hello from Laravel Reverb! WebSocket is working ✅');

        try {
            event(new TestBroadcastEvent($message));

            return response()->json([
                'success' => true,
                'message' => 'Test broadcast fired on [test-channel] → event: test-event',
                'payload' => [
                    'message' => $message,
                    'time'    => now()->toDateTimeString(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Broadcast failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a real notification in the database and broadcast it via NotificationSent.
     * Tests both the DB write and WebSocket broadcast together.
     */
    public function testNotificationBroadcast(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'body'  => 'nullable|string|max:1000',
            'type'  => 'nullable|in:user,card,payment,shield,security,general',
        ]);

        $user = $request->user();

        try {
            $notification = $user->sendNotification(
                title: $request->input('title', '🔔 Test Notification'),
                body: $request->input('body', 'This is a real-time notification from the admin broadcast test panel.'),
                type: $request->input('type', 'user'),
                link: route('admin.dashboard'),
                action: 'test'
            );

            return response()->json([
                'success'      => true,
                'message'      => 'Notification saved to DB and broadcast sent!',
                'notification' => [
                    'id'    => $notification->id,
                    'type'  => $notification->type,
                    'title' => $notification->title,
                    'body'  => $notification->body,
                ],
                'channels'     => [
                    'private' => 'user.' . $user->id,
                    'legacy'  => 'App.Models.User.' . $user->id,
                    'public'  => 'notifications',
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
