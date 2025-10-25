<?php

declare(strict_types=1);

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\DatabaseNotification;
use Modules\Notification\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * @group Notifications
     * @authenticated
     *
     * List notifications for the authenticated user
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $notifications = $request->user('api')
            ->notifications()
            ->latest()
            ->paginate((int) min($request->integer('per_page', 20), 50));

        return NotificationResource::collection($notifications);
    }

    /**
     * @group Notifications
     * @authenticated
     *
     * Mark a notification as read
     */
    public function markAsRead(Request $request, string $notificationId): NotificationResource
    {
        /** @var DatabaseNotification $notification */
        $notification = $request->user('api')
            ->notifications()
            ->findOrFail($notificationId);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return new NotificationResource($notification->refresh());
    }

    /**
     * @group Notifications
     * @authenticated
     *
     * Delete a notification
     */
    public function destroy(Request $request, string $notificationId): JsonResponse
    {
        $request->user('api')
            ->notifications()
            ->whereKey($notificationId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully.',
        ]);
    }
}
