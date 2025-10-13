<?php

namespace Modules\Monetization\Tests\Feature\Monetization;

use Modules\Monetization\Domain\Entities\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_active_plans(): void
    {
        Plan::factory()->create(['active' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/monetization/plans');

        $response->assertOk()->assertJsonStructure(['data' => [['id', 'name', 'slug']]]);
    }
}
