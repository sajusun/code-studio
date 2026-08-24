<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationsController extends Controller
{
    public function __construct(
        protected NotificationService $service
    ) {}

    /**
     * List user notifications
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $userId = $request->user()->id;
        $perPage = $request->integer('per_page', 15);

        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);

        return NotificationResource::collection($notifications);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'count'   => $this->service->unreadCount($request->user()->id),
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $this->service->markAsRead($id, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->service->markAllAsRead($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully.',
        ]);
    }
}
