<?php

namespace App\Http\Controllers\Api\Kpi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kpi\StoreInstallationRequest;
use App\Models\KpiDevice;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class InstallationController extends Controller
{
    /**
     * Record an installation event
     *
     * Saves installation metadata for a device and links it to the KPI device profile.
     *
     * @group KPI
     *
     * @bodyParam device_uuid string required شناسه یکتای دستگاه. Example: 991aa9de-8b0c-4679-a9f2-6a5b3fda0a92
     * @bodyParam installed_at string زمان نصب (ISO 8601). Example: 2024-02-18T09:45:00+03:30
     * @bodyParam app_version string required نسخه برنامه هنگام نصب. Example: 2.1.0
     * @bodyParam platform string required سکوی اجرای برنامه. Example: android
     * @bodyParam os_version string نسخه سیستم‌عامل نصب. Example: ۱۴
     * @bodyParam device_model string مدل دستگاه کاربر. Example: شیائومی ۱۲T
     * @bodyParam install_source string منبع نصب یا مارکت. Example: کافه‌بازار
     * @bodyParam campaign string کمپین نصب. Example: نوروز۱۴۰۳
     * @bodyParam is_reinstall boolean مشخص می‌کند نصب مجدد بوده است. Example: false
     * @bodyParam metadata object داده‌های تکمیلی نصب.
     * @bodyParam user_id integer شناسه کاربر در صورت احراز هویت. Example: 15
     */
    public function store(StoreInstallationRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $installedAt = isset($payload['installed_at'])
            ? CarbonImmutable::parse($payload['installed_at'])
            : CarbonImmutable::now();

        $device = $this->syncDeviceFromPayload($payload, $installedAt);

        $isReinstall = array_key_exists('is_reinstall', $payload)
            ? (bool) $payload['is_reinstall']
            : $device->installations()->exists();

        $installation = $device->installations()->create([
            'user_id' => $request->user()?->id ?? $payload['user_id'] ?? null,
            'installed_at' => $installedAt,
            'app_version' => $payload['app_version'],
            'install_source' => $payload['install_source'] ?? null,
            'campaign' => $payload['campaign'] ?? null,
            'is_reinstall' => $isReinstall,
            'metadata' => $payload['metadata'] ?? null,
        ]);

        return response()->json([
            'data' => [
                'id' => $installation->id,
                'device_uuid' => $device->device_uuid,
                'installed_at' => $installation->installed_at->toIso8601String(),
                'is_reinstall' => $installation->is_reinstall,
            ],
        ], 201);
    }

    private function syncDeviceFromPayload(array $payload, CarbonImmutable $timestamp): KpiDevice
    {
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

        $device->first_seen_at = $device->first_seen_at ?? $timestamp;
        $device->last_seen_at = $timestamp;
        $device->last_heartbeat_at = $device->last_heartbeat_at ?? $timestamp;
        $device->is_active = true;
        $device->save();

        return $device;
    }
}
