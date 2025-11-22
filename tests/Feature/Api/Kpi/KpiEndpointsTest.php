<?php

namespace Tests\Feature\Api\Kpi;

use App\Models\KpiDevice;
use App\Models\KpiEvent;
use App\Models\KpiSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class KpiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_device_registration_creates_device_record(): void
    {
        $deviceUuid = (string) Str::uuid();

        $response = $this->postJson('/api/kpi/devices/register', [
            'device_uuid' => $deviceUuid,
            'platform' => 'android',
            'app_version' => '1.0.0',
            'os_version' => '14',
            'device_model' => 'Pixel 8',
            'device_manufacturer' => 'Google',
            'locale' => 'en',
            'timezone' => 'UTC',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('kpi_devices', [
            'device_uuid' => $deviceUuid,
            'platform' => 'android',
            'app_version' => '1.0.0',
        ]);
    }

    public function test_installation_endpoint_marks_reinstall_automatically(): void
    {
        $deviceUuid = (string) Str::uuid();

        $this->postJson('/api/kpi/installations', [
            'device_uuid' => $deviceUuid,
            'installed_at' => now()->toIso8601String(),
            'app_version' => '1.0.0',
            'platform' => 'android',
        ])->assertCreated();

        $this->postJson('/api/kpi/installations', [
            'device_uuid' => $deviceUuid,
            'installed_at' => now()->addDay()->toIso8601String(),
            'app_version' => '1.1.0',
            'platform' => 'android',
        ])->assertCreated();

        $this->assertDatabaseHas('kpi_installations', [
            'kpi_device_id' => KpiDevice::firstWhere('device_uuid', $deviceUuid)?->id,
            'is_reinstall' => true,
        ]);
    }

    public function test_session_store_and_update_flow(): void
    {
        $deviceUuid = (string) Str::uuid();
        $sessionUuid = (string) Str::uuid();

        $storeResponse = $this->postJson('/api/kpi/sessions', [
            'device_uuid' => $deviceUuid,
            'session_uuid' => $sessionUuid,
            'started_at' => now()->subMinutes(5)->toIso8601String(),
            'ended_at' => now()->subMinutes(1)->toIso8601String(),
            'app_version' => '2.0.0',
            'platform' => 'android',
            'network_type' => 'wifi',
        ]);

        $storeResponse->assertCreated();

        $updateResponse = $this->postJson("/api/kpi/sessions/{$sessionUuid}/update", [
            'ended_at' => now()->toIso8601String(),
            'network_type' => '5g',
        ]);

        $updateResponse->assertOk();

        $session = KpiSession::firstWhere('session_uuid', $sessionUuid);
        $this->assertNotNull($session);
        $this->assertSame('5g', $session->network_type);
        $this->assertNotNull($session->duration_seconds);
    }

    public function test_event_endpoint_persists_multiple_events(): void
    {
        $deviceUuid = (string) Str::uuid();
        $sessionUuid = (string) Str::uuid();

        $this->postJson('/api/kpi/sessions', [
            'device_uuid' => $deviceUuid,
            'session_uuid' => $sessionUuid,
            'started_at' => now()->subMinutes(10)->toIso8601String(),
            'app_version' => '3.0.0',
            'platform' => 'android',
        ])->assertCreated();

        $response = $this->postJson('/api/kpi/events', [
            'device_uuid' => $deviceUuid,
            'session_uuid' => $sessionUuid,
            'events' => [
                [
                    'event_uuid' => (string) Str::uuid(),
                    'event_key' => 'ad_view',
                    'occurred_at' => now()->subMinutes(2)->toIso8601String(),
                ],
                [
                    'event_key' => 'ad_click',
                    'occurred_at' => now()->subMinute()->toIso8601String(),
                    'event_value' => 1,
                ],
            ],
        ]);

        $response->assertCreated();

        $this->assertCount(2, KpiEvent::all());
    }

    public function test_uninstallation_endpoint_deactivates_device(): void
    {
        $deviceUuid = (string) Str::uuid();

        $this->postJson('/api/kpi/devices/register', [
            'device_uuid' => $deviceUuid,
            'platform' => 'ios',
            'app_version' => '1.0.0',
        ])->assertCreated();

        $this->postJson('/api/kpi/uninstallations', [
            'device_uuid' => $deviceUuid,
            'uninstalled_at' => now()->toIso8601String(),
            'reason' => 'user_choice',
        ])->assertCreated();

        $device = KpiDevice::firstWhere('device_uuid', $deviceUuid);
        $this->assertFalse($device->is_active);
        $this->assertDatabaseHas('kpi_uninstallations', [
            'kpi_device_id' => $device->id,
            'reason' => 'user_choice',
        ]);
    }

    public function test_session_metadata_is_merged_when_updating(): void
    {
        $deviceUuid = (string) Str::uuid();
        $sessionUuid = (string) Str::uuid();

        $initialMetadata = [
            'callStatsOrder' => ['calls', 'duration'],
            'last_screen' => 'home',
        ];

        $this->postJson('/api/kpi/sessions', [
            'device_uuid' => $deviceUuid,
            'session_uuid' => $sessionUuid,
            'started_at' => now()->subMinutes(15)->toIso8601String(),
            'ended_at' => now()->subMinutes(5)->toIso8601String(),
            'app_version' => '2.1.0',
            'platform' => 'ios',
            'metadata' => $initialMetadata,
        ])->assertCreated();

        $this->postJson("/api/kpi/sessions/{$sessionUuid}/update", [
            'metadata' => [
                'callStatsOrder' => ['duration', 'calls'],
            ],
            'ended_at' => now()->toIso8601String(),
        ])->assertOk();

        $session = KpiSession::firstWhere('session_uuid', $sessionUuid);
        $this->assertSame(
            ['duration', 'calls'],
            $session->metadata['callStatsOrder']
        );
        $this->assertSame('home', $session->metadata['last_screen']);
    }
}
