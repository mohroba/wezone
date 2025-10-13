<?php

namespace App\Http\Controllers\Api\Kpi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kpi\StoreSessionRequest;
use App\Http\Requests\Kpi\UpdateSessionRequest;
use App\Models\KpiDevice;
use App\Models\KpiSession;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class SessionController extends Controller
{
    /**
     * Start or upsert a session
     *
     * Creates a fresh session or updates an existing one when the same شناسه نشست is provided.
     *
     * @group KPI
     *
     * @bodyParam device_uuid string required شناسه یکتای دستگاه. Example: 4a8bc1dc-4173-4d60-b58d-48d97de5f235
     * @bodyParam session_uuid string required شناسه نشست. Example: 1a6941f0-02a9-4c5a-86ec-17cfebbb5d43
     * @bodyParam started_at string required زمان شروع نشست (ISO 8601). Example: 2024-03-05T12:00:00+03:30
     * @bodyParam ended_at string زمان پایان نشست (ISO 8601). Example: 2024-03-05T12:20:00+03:30
     * @bodyParam duration_seconds integer مدت نشست به ثانیه. Example: 1200
     * @bodyParam app_version string required نسخه برنامه در نشست. Example: 3.2.5
     * @bodyParam platform string سکوی اجرای برنامه. Example: android
     * @bodyParam network_type string نوع شبکه کاربر. Example: وای‌فای
     * @bodyParam city string شهر کاربر. Example: تهران
     * @bodyParam country string کشور کاربر. Example: ایران
     * @bodyParam metadata object داده‌های تکمیلی نشست.
     * @bodyParam user_id integer شناسه کاربر در صورت موجود بودن. Example: 42
     */
    public function store(StoreSessionRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $startedAt = CarbonImmutable::parse($payload['started_at']);
        $endedAt = isset($payload['ended_at']) ? CarbonImmutable::parse($payload['ended_at']) : null;

        $device = $this->syncDevice($payload, $startedAt, $endedAt);
        $userId = $request->user()?->id ?? $payload['user_id'] ?? null;

        $duration = $payload['duration_seconds'] ?? ($endedAt ? $endedAt->diffInSeconds($startedAt) : null);

        $session = KpiSession::updateOrCreate(
            ['session_uuid' => $payload['session_uuid']],
            [
                'kpi_device_id' => $device->id,
                'user_id' => $userId,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'duration_seconds' => $duration,
                'app_version' => $payload['app_version'],
                'platform' => $payload['platform'] ?? $device->platform,
                'os_version' => $payload['os_version'] ?? $device->os_version,
                'network_type' => $payload['network_type'] ?? null,
                'city' => $payload['city'] ?? null,
                'country' => $payload['country'] ?? null,
                'metadata' => $payload['metadata'] ?? null,
            ]
        );

        return response()->json([
            'data' => [
                'session_uuid' => $session->session_uuid,
                'started_at' => $session->started_at->toIso8601String(),
                'ended_at' => optional($session->ended_at)->toIso8601String(),
                'duration_seconds' => $session->duration_seconds,
            ],
        ], $session->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Update an existing session
     *
     * Applies partial updates to a stored session and syncs related device metrics.
     *
     * @group KPI
     *
     * @bodyParam ended_at string زمان پایان نشست (ISO 8601). Example: 2024-03-05T12:45:00+03:30
     * @bodyParam duration_seconds integer مدت نشست به ثانیه. Example: 1800
     * @bodyParam app_version string نسخه جدید برنامه. Example: 3.2.6
     * @bodyParam platform string سکوی اجرای برنامه. Example: android
     * @bodyParam os_version string نسخه سیستم‌عامل. Example: ۱۴
     * @bodyParam network_type string نوع شبکه کاربر. Example: ۵جی
     * @bodyParam city string شهر کاربر. Example: اصفهان
     * @bodyParam country string کشور کاربر. Example: ایران
     * @bodyParam metadata object داده‌های تکمیلی نشست.
     * @bodyParam user_id integer شناسه کاربر در صورت تغییر. Example: 42
     */
    public function update(UpdateSessionRequest $request, KpiSession $session): JsonResponse
    {
        $payload = $request->validated();
        $existingEndedAt = $session->ended_at ? CarbonImmutable::make($session->ended_at) : null;
        $endedAt = isset($payload['ended_at'])
            ? CarbonImmutable::parse($payload['ended_at'])
            : $existingEndedAt;

        $session->fill(Arr::only($payload, [
            'app_version',
            'platform',
            'os_version',
            'network_type',
            'city',
            'country',
            'metadata',
        ]));

        if ($endedAt) {
            $session->ended_at = $endedAt;
        }

        if (array_key_exists('duration_seconds', $payload)) {
            $session->duration_seconds = $payload['duration_seconds'];
        } elseif ($endedAt) {
            $session->duration_seconds = $endedAt->diffInSeconds(CarbonImmutable::make($session->started_at));
        }

        if (isset($payload['user_id'])) {
            $session->user_id = $payload['user_id'];
        } elseif ($request->user()) {
            $session->user_id = $request->user()->id;
        }

        $session->save();

        $device = $session->device;
        if ($device) {
            $device->fill(Arr::only($payload, [
                'app_version',
                'platform',
                'os_version',
            ]));
            if ($endedAt) {
                $device->last_seen_at = $endedAt;
                $device->last_heartbeat_at = $endedAt;
            }
            $device->save();
        }

        return response()->json([
            'data' => [
                'session_uuid' => $session->session_uuid,
                'ended_at' => optional($session->ended_at)->toIso8601String(),
                'duration_seconds' => $session->duration_seconds,
            ],
        ]);
    }

    private function syncDevice(array $payload, CarbonImmutable $startedAt, ?CarbonImmutable $endedAt = null): KpiDevice
    {
        $device = KpiDevice::firstOrNew([
            'device_uuid' => $payload['device_uuid'],
        ]);

        $device->fill(Arr::only($payload, [
            'platform',
            'app_version',
            'os_version',
        ]));

        if (!$device->platform) {
            $device->platform = $payload['platform'] ?? 'unknown';
        }

        $device->first_seen_at = $device->first_seen_at ?? $startedAt;
        $device->last_seen_at = $endedAt ?? $startedAt;
        if ($endedAt) {
            $device->last_heartbeat_at = $endedAt;
        }
        $device->is_active = true;
        $device->save();

        return $device;
    }
}
