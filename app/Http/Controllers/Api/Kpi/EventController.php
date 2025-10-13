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

class EventController extends Controller
{
    /**
     * Store KPI events
     *
     * Registers one یا چند رخداد KPI برای دستگاه و نشست مشخص‌شده.
     *
     * @group KPI
     *
     * @bodyParam device_uuid string required شناسه یکتای دستگاه. Example: 3d5c8b93-5a35-49a5-9c31-1aa7f6676b1d
     * @bodyParam session_uuid string شناسه نشست مرتبط. Example: 0f8fad5b-d9cb-469f-a165-70867728950e
     * @bodyParam platform string سکوی اجرای برنامه برای انتساب دستگاه. Example: android
     * @bodyParam user_id integer شناسه کاربر ثبت‌کننده. Example: 24
     * @bodyParam events array required آرایه‌ای از رخدادها.
     * @bodyParam events[].event_uuid string شناسه یکتای رخداد جهت upsert. Example: 6f9619ff-8b86-d011-b42d-00c04fc964ff
     * @bodyParam events[].event_key string required کلید رخداد. Example: بازدید_آگهی
     * @bodyParam events[].event_name string عنوان انسانی رخداد. Example: مشاهده صفحه جزئیات
     * @bodyParam events[].event_category string دسته‌بندی رخداد. Example: آگهی
     * @bodyParam events[].event_value number مقدار رخداد. Example: 1
     * @bodyParam events[].occurred_at string زمان وقوع رخداد (ISO 8601). Example: 2024-03-05T12:05:00+03:30
     * @bodyParam events[].metadata object داده‌های تکمیلی رخداد.
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
