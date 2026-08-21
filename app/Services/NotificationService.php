<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function __construct(
        protected FirebaseService $firebaseService,
        protected BroadcastService $broadcastService,
    ) {}

    public function send(
        User|int $user,
        string $title,
        string $body,
        string $type = 'general',
        ?string $referenceType = null,
        string|int|null $referenceId = null,
        ?string $action = null,
        ?string $link = null,
        array $meta = []
    ): Notification {
        $userId = $user instanceof User ? $user->id : $user;

        $notification = Notification::create([
            'user_id'        => $userId,
            'type'           => $type,
            'title'          => $title,
            'body'           => $body,
            'reference_type' => $referenceType,
            'reference_id'   => (string) $referenceId,
            'action'         => $action,
            'link'           => $link,
            'meta'           => $meta,
        ]);

        if (config('notifications.channels.firebase', true)) {
            $this->firebaseService->send($notification);
        }

        if (config('notifications.channels.broadcast', true)) {
            $this->broadcastService->send($notification);
        }

        return $notification;
    }

    public function sendMany(
        iterable $users,
        string $title,
        string $body,
        string $type = 'general',
        ?string $referenceType = null,
        string|int|null $referenceId = null,
        ?string $action = null,
        ?string $link = null,
        array $meta = []
    ): void {
        foreach ($users as $user) {
            $this->send($user, $title, $body, $type, $referenceType, $referenceId, $action, $link, $meta);
        }
    }

    public function markAsRead(string $notificationId, int $userId): bool
    {
        return (bool) Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(int $userId): bool
    {
        return (bool) Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function unreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)->whereNull('read_at')->count();
    }
}
