<?php

namespace App\Http\Controllers\Api\Kpi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kpi\StoreEventRequest;
use App\Models\KpiDevice;
use App\Models\KpiEvent;
use App\Models\KpiSession;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * @group KPI
 *
 * Record KPI events against tracked sessions and devices.
 */
class EventController extends Controller
{
    /**
     * Record KPI events for a device session.
     *
     * @group KPI
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $now = CarbonImmutable::now();
        $userId = $request->user()?->id ?? $payload['user_id'] ?? null;

        $device = KpiDevice::firstOrNew([
            'device_uuid' => $payload['device_uuid'],
        ]);

        if (isset($payload['platform'])) {
            $device->platform = $payload['platform'];
        } elseif (!$device->platform) {
            $device->platform = 'unknown';
        }

        if (!$device->first_seen_at) {
            $device->first_seen_at = $now;
        }

        $device->last_seen_at = $device->last_seen_at ?? $now;
        $device->last_heartbeat_at = $device->last_heartbeat_at ?? $device->last_seen_at;
        $device->is_active = true;
        $device->save();

        $session = null;
        if (!empty($payload['session_uuid'])) {
            $session = KpiSession::where('session_uuid', $payload['session_uuid'])->first();
            if ($session && $userId && !$session->user_id) {
                $session->user_id = $userId;
                $session->save();
            }
        }

        $createdEvents = [];
        $latestActivity = CarbonImmutable::make($device->last_seen_at) ?? $now;

        DB::transaction(function () use ($payload, $device, $session, $userId, $now, &$createdEvents, &$latestActivity) {
            foreach ($payload['events'] as $eventPayload) {
                $occurredAt = isset($eventPayload['occurred_at'])
                    ? CarbonImmutable::parse($eventPayload['occurred_at'])
                    : $now;

                $latestActivity = $occurredAt->greaterThan($latestActivity) ? $occurredAt : $latestActivity;

                $data = [
                    'kpi_device_id' => $device->id,
                    'kpi_session_id' => $session?->id,
                    'user_id' => $userId,
                    'event_key' => $eventPayload['event_key'],
                    'event_name' => $eventPayload['event_name'] ?? null,
                    'event_category' => $eventPayload['event_category'] ?? null,
                    'event_value' => $eventPayload['event_value'] ?? null,
                    'occurred_at' => $occurredAt,
                    'metadata' => $eventPayload['metadata'] ?? null,
                ];

                if (!empty($eventPayload['event_uuid'])) {
                    $event = KpiEvent::updateOrCreate(
                        ['event_uuid' => $eventPayload['event_uuid']],
                        array_merge($data, ['event_uuid' => $eventPayload['event_uuid']])
                    );
                } else {
                    $event = $device->events()->create($data);
                }

                $createdEvents[] = [
                    'id' => $event->id,
                    'event_uuid' => $event->event_uuid,
                    'event_key' => $event->event_key,
                    'occurred_at' => $event->occurred_at->toIso8601String(),
                ];
            }
        });

        $device->last_seen_at = $latestActivity;
        $device->last_heartbeat_at = $latestActivity;
        $device->save();

        if ($session && $latestActivity->greaterThan(CarbonImmutable::make($session->started_at))) {
            $session->ended_at = $session->ended_at && $latestActivity->lessThan(CarbonImmutable::make($session->ended_at))
                ? $session->ended_at
                : $latestActivity;
            if ($session->ended_at) {
                $session->duration_seconds = $session->ended_at->diffInSeconds($session->started_at);
            }
            $session->save();
        }

        return response()->json([
            'data' => $createdEvents,
        ], 201);
    }
}
