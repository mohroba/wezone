<?php

namespace Modules\Auth\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Auth\Models\Profile;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function migrateDatabases(): void
    {
        $this->artisan('migrate:fresh', array_merge(
            $this->migrateFreshUsing(),
            ['--path' => 'database/migrations']
        ));

        foreach ([
            'Modules/Auth/database/migrations',
            'Modules/Ad/database/migrations',
        ] as $path) {
            $absolutePath = base_path($path);

            if (is_dir($absolutePath)) {
                $this->artisan('migrate', [
                    '--path' => $absolutePath,
                    '--realpath' => true,
                ]);
            }
        }
    }

    public function test_profile_show_endpoint_returns_ads_metrics(): void
    {
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();

        Ad::factory()->for($user, 'user')->create(['view_count' => 5]);
        Ad::factory()->for($user, 'user')->create(['view_count' => 7]);
        Ad::factory()->create(['view_count' => 50]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/auth/profile');

        $response
            ->assertOk()
            ->assertJsonPath('data.profile.ads_count', 2)
            ->assertJsonPath('data.profile.total_ad_views', 12);
    }

    public function test_profile_update_endpoint_returns_ads_metrics(): void
    {
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();

        Ad::factory()->for($user, 'user')->create(['view_count' => 1]);
        Ad::factory()->for($user, 'user')->create(['view_count' => 4]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/profile', [
            'first_name' => 'Updated',
            'last_name' => 'Name',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.profile.ads_count', 2)
            ->assertJsonPath('data.profile.total_ad_views', 5)
            ->assertJsonPath('data.profile.full_name', 'Updated Name');
    }
}
