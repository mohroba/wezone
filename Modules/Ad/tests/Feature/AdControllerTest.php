<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdJob;
use Tests\TestCase;

class AdControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_ad_and_nested_advertisable(): void
    {
        $user = User::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'slug' => 'sporty-coupe',
            'title' => 'Sporty coupe for sale',
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => [
                    'brand_id' => 5,
                    'model_id' => 9,
                    'year' => 2023,
                    'mileage' => 15000,
                ],
            ],
        ];

        $response = $this->postJson('/api/ads', $payload);

        $response->assertCreated();
        $response->assertJsonPath('data.slug', 'sporty-coupe');
        $response->assertJsonPath('data.advertisable.brand_id', 5);
        $response->assertJsonPath('data.advertisable.slug', 'sporty-coupe');

        $this->assertDatabaseHas('ads', [
            'slug' => 'sporty-coupe',
            'advertisable_type' => AdCar::class,
        ]);

        $car = AdCar::query()->first();
        $this->assertNotNull($car);
        $this->assertSame('sporty-coupe', $car->slug);
        $this->assertSame(15000, (int) $car->mileage);
    }

    public function test_update_mutates_nested_advertisable_and_synchronises_slug(): void
    {
        $user = User::factory()->create();
        $adSlug = 'city-hatchback';
        $car = AdCar::create([
            'slug' => $adSlug,
            'brand_id' => 1,
            'model_id' => 2,
            'year' => 2020,
            'mileage' => 40000,
        ]);

        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => $adSlug,
            'title' => 'Reliable hatchback',
        ]);

        $payload = [
            'slug' => 'city-hatchback-updated',
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => [
                    'mileage' => 41000,
                    'year' => 2021,
                ],
            ],
        ];

        $response = $this->postJson("/api/ads/{$ad->id}/update", $payload);

        $response->assertOk();
        $response->assertJsonPath('data.slug', 'city-hatchback-updated');
        $response->assertJsonPath('data.advertisable.slug', 'city-hatchback-updated');
        $response->assertJsonPath('data.advertisable.mileage', 41000);

        $ad->refresh();
        $this->assertSame('city-hatchback-updated', $ad->slug);
        $this->assertSame('city-hatchback-updated', $ad->advertisable->slug);
        $this->assertSame(2021, (int) $ad->advertisable->year);
    }

    public function test_store_rejects_invalid_advertisable_payload(): void
    {
        $user = User::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'slug' => 'remote-role',
            'title' => 'Remote role',
            'advertisable' => [
                'type' => AdJob::class,
                'attributes' => [
                    'company_name' => 'Acme Corp',
                    // Missing position_title and employment_type
                ],
            ],
        ];

        $response = $this->postJson('/api/ads', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'advertisable.attributes.position_title',
            'advertisable.attributes.employment_type',
        ]);
    }

    public function test_seen_endpoint_increments_view_count_only_once_per_user(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['view_count' => 2]);

        Passport::actingAs($user);

        $firstResponse = $this->postJson("/api/ads/{$ad->id}/seen");
        $firstResponse->assertOk();
        $firstResponse->assertJsonPath('data.incremented', true);
        $firstResponse->assertJsonPath('data.view_count', 3);

        $secondResponse = $this->postJson("/api/ads/{$ad->id}/seen");
        $secondResponse->assertOk();
        $secondResponse->assertJsonPath('data.incremented', false);
        $secondResponse->assertJsonPath('data.view_count', 3);

        $this->assertDatabaseHas('ads', [
            'id' => $ad->id,
            'view_count' => 3,
        ]);
    }

    public function test_seen_endpoint_prevents_duplicate_guest_views_using_same_fingerprint(): void
    {
        $ad = Ad::factory()->create(['view_count' => 0]);

        $server = [
            'REMOTE_ADDR' => '203.0.113.10',
            'HTTP_USER_AGENT' => 'IntegrationTestAgent/1.0',
        ];

        $firstResponse = $this->withServerVariables($server)
            ->postJson("/api/ads/{$ad->id}/seen");
        $firstResponse->assertOk();
        $firstResponse->assertJsonPath('data.incremented', true);
        $firstResponse->assertJsonPath('data.view_count', 1);

        $secondResponse = $this->withServerVariables($server)
            ->postJson("/api/ads/{$ad->id}/seen");
        $secondResponse->assertOk();
        $secondResponse->assertJsonPath('data.incremented', false);
        $secondResponse->assertJsonPath('data.view_count', 1);

        $this->assertDatabaseHas('ads', [
            'id' => $ad->id,
            'view_count' => 1,
        ]);
    }

    public function test_seen_endpoint_disallows_get_requests(): void
    {
        $ad = Ad::factory()->create();

        $this->getJson("/api/ads/{$ad->id}/seen")->assertStatus(405);
    }
}
