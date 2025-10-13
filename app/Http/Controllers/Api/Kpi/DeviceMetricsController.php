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
     * Register or update a KPI device heartbeat profile.
     *
     * @group Ads Review
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
     * Record a KPI device heartbeat.
     *
     * @group Ads Review
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
