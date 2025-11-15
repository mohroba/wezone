<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdFavorite;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Tests\Support\RefreshesAdDatabase;
use Tests\TestCase;

class AdExploreControllerTest extends TestCase
{
    use RefreshesAdDatabase;

    public function test_explore_prioritises_promoted_and_related_ads(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $type = AdvertisableType::factory()->create(['key' => 'cars', 'model_class' => Ad::class]);

        $primaryCategory = AdCategory::create([
            'name' => 'Cars',
            'slug' => 'cars',
            'advertisable_type_id' => $type->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $secondaryCategory = AdCategory::create([
            'name' => 'Real Estate',
            'slug' => 'real-estate',
            'advertisable_type_id' => $type->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $promotedAd = Ad::factory()->create([
            'status' => 'published',
            'featured_until' => now()->addDay(),
            'priority_score' => 10,
            'advertisable_type_id' => $type->id,
            'advertisable_type' => $type->model_class,
        ]);
        $promotedAd->categories()->attach($secondaryCategory->id, ['is_primary' => true]);

        $relatedAd = Ad::factory()->create([
            'status' => 'published',
            'featured_until' => null,
            'priority_score' => 0,
            'advertisable_type_id' => $type->id,
            'advertisable_type' => $type->model_class,
        ]);
        $relatedAd->categories()->attach($primaryCategory->id, ['is_primary' => true]);

        $otherAd = Ad::factory()->create([
            'status' => 'published',
            'advertisable_type_id' => $type->id,
            'advertisable_type' => $type->model_class,
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
