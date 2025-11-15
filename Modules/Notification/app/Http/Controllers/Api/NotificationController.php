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
    /**
     * List notifications for the authenticated user.
     *
     * Fetch paginated notifications ordered by newest first. By default, only unread
     * notifications are returned.
     *
     * @group Notifications
     * @authenticated
     *
     * @queryParam per_page int The number of notifications to return per page (1-100). Example: 20
     * @queryParam page int The current pagination page. Example: 2
     * @queryParam include_read boolean Include notifications that have already been read. Example: true
     *
     * @response 200 scenario="Unread notifications" {
     *   "data": [
     *     {
     *       "id": "8550c7b4-6c93-4f23-94b2-7b7f46cb3d09",
     *       "type": "Modules\\Notification\\Notifications\\AdLiked",
     *       "data": {
     *         "title": "Your ad received a like",
     *         "body": "User123 liked your ad."
     *       },
     *       "read_at": null,
     *       "acknowledged_at": null,
     *       "created_at": "2024-02-18T11:20:15+00:00",
     *       "updated_at": "2024-02-18T11:20:15+00:00"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "per_page": 20,
     *     "to": 1,
     *     "total": 1,
     *     "links": {
     *       "first": "https://example.com/api/v1/notifications?page=1",
     *       "last": "https://example.com/api/v1/notifications?page=1",
     *       "prev": null,
     *       "next": null
     *     }
     *   }
     * }
     */
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

    /**
     * Mark a notification as read.
     *
     * @group Notifications
     * @authenticated
     *
     * @urlParam notification string required The notification identifier.
     *
     * @response 200 {
     *   "id": "8550c7b4-6c93-4f23-94b2-7b7f46cb3d09",
     *   "read_at": "2024-02-18T12:41:09+00:00"
     * }
     */
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

    /**
     * Mark every unread notification as read.
     *
     * @group Notifications
     * @authenticated
     *
     * @response 200 {
     *   "updated": 4
     * }
     */
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

    /**
     * Acknowledge a notification without marking it as read.
     *
     * Use this when a notification should be tracked separately from the read
     * timestamp (for example, when dismissing announcements).
     *
     * @group Notifications
     * @authenticated
     *
     * @urlParam notification string required The notification identifier.
     *
     * @response 200 {
     *   "id": "8550c7b4-6c93-4f23-94b2-7b7f46cb3d09",
     *   "acknowledged_at": "2024-02-18T12:41:09+00:00"
     * }
     */
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
