<?php

namespace Modules\Notification\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $includeRead = $request->boolean('include_read');

        $query = $user->notifications()->latest('created_at');

        if (!$includeRead) {
            $query->whereNull('read_at');
        }

        /** @var LengthAwarePaginator $notifications */
        $notifications = $query->paginate($perPage);

        $data = $notifications->getCollection()->map(fn (DatabaseNotification $notification): array => [
                'id' => $notification->getKey(),
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $this->formatTimestamp($notification->read_at),
                'acknowledged_at' => $this->formatTimestamp($notification->acknowledged_at ?? null),
                'created_at' => $this->formatTimestamp($notification->created_at),
                'updated_at' => $this->formatTimestamp($notification->updated_at),
            ])->values();

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'from' => $notifications->firstItem(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'to' => $notifications->lastItem(),
                'total' => $notifications->total(),
                'links' => [
                    'first' => $notifications->url(1),
                    'last' => $notifications->url($notifications->lastPage()),
                    'prev' => $notifications->previousPageUrl(),
                    'next' => $notifications->nextPageUrl(),
                ],
            ],
        ]);
    }

    public function markAsRead(Request $request, DatabaseNotification $notification): JsonResponse
    {
        $user = $request->user();
        $this->assertNotificationBelongsToUser($notification, $user);

        if ($notification->read_at === null) {
            $now = Carbon::now();

            $notification->forceFill([
                'read_at' => $now,
                'updated_at' => $now,
            ])->save();
        }

        return response()->json([
            'id' => $notification->getKey(),
            'read_at' => $this->formatTimestamp($notification->read_at),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $now = Carbon::now();

        $updated = $user->notifications()
            ->whereNull('read_at')
            ->update([
                'read_at' => $now,
                'updated_at' => $now,
            ]);

        return response()->json([
            'updated' => $updated,
        ]);
    }

    public function acknowledge(Request $request, DatabaseNotification $notification): JsonResponse
    {
        $user = $request->user();
        $this->assertNotificationBelongsToUser($notification, $user);

        if (($notification->acknowledged_at ?? null) === null) {
            $now = Carbon::now();

            $notification->forceFill([
                'acknowledged_at' => $now,
                'updated_at' => $now,
            ])->save();
        }

        return response()->json([
            'id' => $notification->getKey(),
            'acknowledged_at' => $this->formatTimestamp($notification->acknowledged_at ?? null),
        ]);
    }

    private function assertNotificationBelongsToUser(DatabaseNotification $notification, $user): void
    {
        $notifiableClass = method_exists($user, 'getMorphClass')
            ? $user->getMorphClass()
            : $user::class;

        if ($notification->notifiable_type !== $notifiableClass || $notification->notifiable_id !== $user->getKey()) {
            abort(404);
        }
    }

    private function formatTimestamp($timestamp): ?string
    {
        if ($timestamp === null) {
            return null;
        }

        if ($timestamp instanceof Carbon) {
            return $timestamp->toISOString();
        }

        if ($timestamp instanceof \DateTimeInterface) {
            return Carbon::instance($timestamp)->toISOString();
        }

        return Carbon::parse((string) $timestamp)->toISOString();
    }
}
