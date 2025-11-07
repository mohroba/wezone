<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdFavorite;
use Tests\TestCase;

class AdExploreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_explore_prioritises_promoted_and_related_ads(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $primaryCategory = AdCategory::create([
            'name' => 'Cars',
            'slug' => 'cars',
            'depth' => 0,
            'path' => 'cars',
            'is_active' => true,
        ]);

        $secondaryCategory = AdCategory::create([
            'name' => 'Real Estate',
            'slug' => 'real-estate',
            'depth' => 0,
            'path' => 'real-estate',
            'is_active' => true,
        ]);

        $promotedAd = Ad::factory()->create([
            'status' => 'published',
            'featured_until' => now()->addDay(),
            'priority_score' => 10,
        ]);
        $promotedAd->categories()->attach($secondaryCategory->id, ['is_primary' => true]);

        $relatedAd = Ad::factory()->create([
            'status' => 'published',
            'featured_until' => null,
            'priority_score' => 0,
        ]);
        $relatedAd->categories()->attach($primaryCategory->id, ['is_primary' => true]);

        $otherAd = Ad::factory()->create([
            'status' => 'published',
        ]);
        $otherAd->categories()->attach($secondaryCategory->id, ['is_primary' => true]);

        AdFavorite::create([
            'ad_id' => $relatedAd->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/ads/explore');

        $response->assertOk();

        $ids = array_column($response->json('data'), 'id');

        $this->assertSame($promotedAd->id, $ids[0]);
        $this->assertSame($relatedAd->id, $ids[1]);
        $this->assertContains($otherAd->id, $ids);
    }
}
