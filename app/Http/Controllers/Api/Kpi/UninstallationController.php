<?php

namespace App\Http\Controllers\Api\Kpi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kpi\StoreUninstallationRequest;
use App\Models\KpiDevice;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class UninstallationController extends Controller
{
    /**
     * Record an uninstallation event
     *
     * Marks a device as inactive and stores the uninstall reason for KPI analysis.
     *
     * @group KPI
     *
     * @bodyParam device_uuid string required شناسه یکتای دستگاه. Example: 77b47f61-3eea-4bb8-bf68-0c7e3c70fbb5
     * @bodyParam uninstalled_at string زمان حذف برنامه (ISO 8601). Example: 2024-03-06T18:10:00+03:30
     * @bodyParam platform string سکوی اجرای برنامه. Example: ios
     * @bodyParam app_version string نسخه برنامه هنگام حذف. Example: 3.2.6
     * @bodyParam reason string دلیل حذف. Example: user_choice
     * @bodyParam report_source string منبع گزارش حذف. Example: in_app
     * @bodyParam metadata object داده‌های تکمیلی مربوط به حذف.
     * @bodyParam user_id integer شناسه کاربر مرتبط. Example: 8
     */
    public function store(StoreUninstallationRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $uninstalledAt = isset($payload['uninstalled_at'])
            ? CarbonImmutable::parse($payload['uninstalled_at'])
            : CarbonImmutable::now();

        $device = KpiDevice::firstOrNew([
            'device_uuid' => $payload['device_uuid'],
        ]);

        if (isset($payload['platform'])) {
            $device->platform = $payload['platform'];
        } elseif (!$device->platform) {
            $device->platform = 'unknown';
        }

        if (!$device->first_seen_at) {
            $device->first_seen_at = $uninstalledAt;
        }

        $device->last_seen_at = $uninstalledAt;
        $device->last_heartbeat_at = $device->last_heartbeat_at ?? $uninstalledAt;
        $device->is_active = false;
        $device->save();

        $uninstallation = $device->uninstallations()->create([
            'user_id' => $request->user()?->id ?? $payload['user_id'] ?? null,
            'uninstalled_at' => $uninstalledAt,
            'app_version' => $payload['app_version'] ?? null,
            'reason' => $payload['reason'] ?? null,
            'report_source' => $payload['report_source'] ?? null,
            'metadata' => $payload['metadata'] ?? null,
        ]);

        return response()->json([
            'data' => [
                'id' => $uninstallation->id,
                'device_uuid' => $device->device_uuid,
                'uninstalled_at' => $uninstallation->uninstalled_at->toIso8601String(),
            ],
        ], 201);
    }
}
