<?php

namespace App\Http\Controllers\Api\Kpi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kpi\StoreUninstallationRequest;
use App\Models\KpiDevice;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class UninstallationController extends Controller
{
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
