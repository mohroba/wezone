<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Tests\TestCase;

class ProfileMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_response_includes_ad_statistics_and_updates_last_seen(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Ad::factory()->create([
            'user_id' => $user->id,
            'view_count' => 5,
        ]);
        Ad::factory()->create([
            'user_id' => $user->id,
            'view_count' => 3,
        ]);
        Ad::factory()->create([
            'user_id' => $otherUser->id,
            'view_count' => 10,
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/auth/profile');

        $response->assertOk();
        $response->assertJsonPath('data.profile.stats.ads_count', 2);
        $response->assertJsonPath('data.profile.stats.ads_total_views', 8);
        $this->assertNotNull(data_get($response->json(), 'data.profile.stats.last_seen_at'));

        $user->refresh();
        $this->assertNotNull($user->last_seen_at);
    }
}
