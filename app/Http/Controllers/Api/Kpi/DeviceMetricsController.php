<?php

namespace App\Http\Controllers\Api\Kpi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kpi\DeviceHeartbeatRequest;
use App\Http\Requests\Kpi\RegisterDeviceRequest;
use App\Models\KpiDevice;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class DeviceMetricsController extends Controller
{
    /**
     * Register or update a KPI device
     *
     * Persists a device profile for KPI tracking or refreshes an existing record with the latest telemetry.
     *
     * @group KPI
     *
     * @bodyParam device_uuid string required شناسه یکتای دستگاه. Example: 6ff8f7f6-1eb3-3525-be4a-3932c805afed
     * @bodyParam platform string required سکوی اجرای برنامه. Example: android
     * @bodyParam app_version string required نسخه برنامه نصب‌شده. Example: 1.0.0
     * @bodyParam os_version string نسخه سیستم‌عامل دستگاه. Example: ۱۴
     * @bodyParam device_model string مدل دستگاه کاربر. Example: گلکسی A54
     * @bodyParam device_manufacturer string سازنده دستگاه. Example: سامسونگ
     * @bodyParam locale string شناسه زبان کاربر. Example: fa
     * @bodyParam timezone string منطقه زمانی دستگاه. Example: Asia/Tehran
     * @bodyParam push_token string توکن پوش نوتیفیکیشن. Example: fcm_token_123
     * @bodyParam first_seen_at string تاریخ اولین مشاهده (ISO 8601). Example: 2024-03-01T08:30:00+03:30
     * @bodyParam last_seen_at string تاریخ آخرین مشاهده (ISO 8601). Example: 2024-03-03T10:15:00+03:30
     * @bodyParam last_heartbeat_at string تاریخ آخرین ضربان (ISO 8601). Example: 2024-03-03T10:20:00+03:30
     * @bodyParam extra object داده‌های تکمیلی دلخواه.
     */
    public function register(RegisterDeviceRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $timestamp = CarbonImmutable::now();

        $device = KpiDevice::firstOrNew([
            'device_uuid' => $payload['device_uuid'],
        ]);

        $device->fill(Arr::only($payload, [
            'platform',
            'app_version',
            'os_version',
            'device_model',
            'device_manufacturer',
            'locale',
            'timezone',
            'push_token',
            'extra',
        ]));

        if (!$device->platform) {
            $device->platform = $payload['platform'] ?? 'unknown';
        }

        $device->first_seen_at = isset($payload['first_seen_at'])
            ? CarbonImmutable::parse($payload['first_seen_at'])
            : ($device->first_seen_at ?? CarbonImmutable::parse($payload['last_seen_at'] ?? $timestamp));

        $device->last_seen_at = isset($payload['last_seen_at'])
            ? CarbonImmutable::parse($payload['last_seen_at'])
            : ($device->last_seen_at ?? $timestamp);

        if (isset($payload['last_heartbeat_at'])) {
            $device->last_heartbeat_at = CarbonImmutable::parse($payload['last_heartbeat_at']);
        }

        if (!$device->exists) {
            $device->last_heartbeat_at ??= $device->first_seen_at;
        }

        $device->is_active = true;
        $device->save();

        return response()->json([
            'data' => [
                'device_uuid' => $device->device_uuid,
                'created' => $device->wasRecentlyCreated,
                'first_seen_at' => optional($device->first_seen_at)->toIso8601String(),
                'last_seen_at' => optional($device->last_seen_at)->toIso8601String(),
                'last_heartbeat_at' => optional($device->last_heartbeat_at)->toIso8601String(),
            ],
        ], $device->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Send a device heartbeat
     *
     * Records a lightweight heartbeat from an existing device and keeps the device active for KPI reporting.
     *
     * @group KPI
     *
     * @bodyParam device_uuid string required شناسه یکتای دستگاه. Example: 6ff8f7f6-1eb3-3525-be4a-3932c805afed
     * @bodyParam last_seen_at string آخرین زمان مشاهده (ISO 8601). Example: 2024-03-03T10:15:00+03:30
     * @bodyParam last_heartbeat_at string آخرین ضربان (ISO 8601). Example: 2024-03-03T10:20:00+03:30
     * @bodyParam app_version string نسخه برنامه فعال. Example: 1.0.1
     * @bodyParam platform string سکوی اجرای برنامه. Example: ios
     * @bodyParam os_version string نسخه سیستم‌عامل دستگاه. Example: 17.4
     * @bodyParam extra object داده‌های تکمیلی دلخواه.
     */
    public function heartbeat(DeviceHeartbeatRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $timestamp = CarbonImmutable::now();

        $device = KpiDevice::firstOrNew([
            'device_uuid' => $payload['device_uuid'],
        ]);

        $device->fill(Arr::only($payload, [
            'platform',
            'app_version',
            'os_version',
            'extra',
        ]));

        if (!$device->platform) {
            $device->platform = $payload['platform'] ?? $device->platform ?? 'unknown';
        }

        if (!$device->first_seen_at) {
            $device->first_seen_at = CarbonImmutable::parse($payload['last_seen_at'] ?? $timestamp);
        }

        $device->last_seen_at = isset($payload['last_seen_at'])
            ? CarbonImmutable::parse($payload['last_seen_at'])
            : $timestamp;

        $device->last_heartbeat_at = isset($payload['last_heartbeat_at'])
            ? CarbonImmutable::parse($payload['last_heartbeat_at'])
            : $timestamp;

        $device->is_active = true;
        $device->save();

        return response()->json([
            'data' => [
                'device_uuid' => $device->device_uuid,
                'last_seen_at' => optional($device->last_seen_at)->toIso8601String(),
                'last_heartbeat_at' => optional($device->last_heartbeat_at)->toIso8601String(),
            ],
        ]);
    }
}
