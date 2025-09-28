<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Settings\Models\Setting;
use Tests\TestCase;

class SettingsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_settings_index_returns_all_public_entries(): void
    {
        Setting::query()->create([
            'key' => 'privacy_policy',
            'value' => 'Our privacy commitment.',
        ]);

        $response = $this->getJson('/api/settings');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.settings.0.key', 'privacy_policy')
            ->assertJsonPath('data.settings.0.value', 'Our privacy commitment.')
            ->assertJsonPath('data.settings.0.is_public', true)
            ->assertJsonPath('data.settings.1.key', 'about_us')
            ->assertJsonPath('data.settings.1.value', null)
            ->assertJsonPath('data.settings.1.is_public', true);
    }

    public function test_public_settings_show_returns_single_entry(): void
    {
        Setting::query()->create([
            'key' => 'about_us',
            'value' => 'We build great apps.',
        ]);

        $response = $this->getJson('/api/settings/about_us');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.setting.key', 'about_us')
            ->assertJsonPath('data.setting.value', 'We build great apps.')
            ->assertJsonPath('data.setting.is_public', true);
    }

    public function test_admin_can_view_and_update_settings_via_post(): void
    {
        Setting::query()->create([
            'key' => 'about_us',
            'value' => 'First version.',
        ]);

        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->getJson('/api/admin/settings')
            ->assertOk()
            ->assertJsonPath('data.settings.0.key', 'privacy_policy')
            ->assertJsonPath('data.settings.1.value', 'First version.');

        $payload = [
            'privacy_policy' => 'Updated privacy details.',
            'about_us' => 'Second version.',
        ];

        $response = $this->postJson('/api/admin/settings', $payload);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.settings.0.value', 'Updated privacy details.')
            ->assertJsonPath('data.settings.1.value', 'Second version.');

        $this->assertDatabaseHas('settings', [
            'key' => 'privacy_policy',
            'value' => 'Updated privacy details.',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'about_us',
            'value' => 'Second version.',
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $this->postJson('/api/admin/settings', [
            'privacy_policy' => 'Should not persist.',
        ])->assertUnauthorized();
    }
}
