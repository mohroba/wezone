<?php

namespace Tests\Feature\Ad;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Services\CategoryHierarchyManager;
use Tests\TestCase;
use App\Models\User;

class AdApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_ad_with_categories(): void
    {
        $user = User::factory()->create();
        $type = AdvertisableType::factory()->create([
            'model_class' => AdCar::class,
        ]);
        $category = AdCategory::create([
            'slug' => 'sedan',
            'name' => 'Sedan',
            'advertisable_type_id' => $type->id,
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($category);
        $category->refresh();

        $response = $this->postJson('/api/ads', [
            'user_id' => $user->id,
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => [
                    'brand_id' => 1,
                    'model_id' => 1,
                    'year' => 2024,
                    'slug' => 'peugeot-206',
                ],
            ],
            'slug' => 'peugeot-206-2024',
            'title' => 'Peugeot 206 2024',
            'description' => 'Brand new condition.',
            'status' => 'draft',
            'price_amount' => 450000000,
            'price_currency' => 'IRR',
            'is_negotiable' => true,
            'contact_channel' => ['phone' => '123456789'],
            'categories' => [
                ['id' => $category->id, 'is_primary' => true, 'assigned_by' => $user->id],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.slug', 'peugeot-206-2024')
            ->assertJsonPath('data.categories.0.pivot.is_primary', true);

        $this->assertDatabaseHas('ads', [
            'slug' => 'peugeot-206-2024',
            'user_id' => $user->id,
            'advertisable_type' => AdCar::class,
        ]);

        $this->assertDatabaseHas('ad_category_ad', [
            'ad_id' => $response->json('data.id'),
            'category_id' => $category->id,
            'is_primary' => true,
        ]);
    }

    public function test_it_updates_ad_and_records_status_and_slug_history(): void
    {
        $user = User::factory()->create();
        $type = AdvertisableType::factory()->create([
            'model_class' => AdCar::class,
        ]);
        $car = AdCar::create([
            'slug' => 'samand',
            'brand_id' => 1,
            'model_id' => 1,
            'year' => 2020,
        ]);

        $category = AdCategory::create([
            'slug' => 'hatchback',
            'name' => 'Hatchback',
            'advertisable_type_id' => $type->id,
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($category);
        $category->refresh();

        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'advertisable_type_id' => $type->id,
            'slug' => 'samand-2020',
            'title' => 'Samand 2020',
            'status' => 'draft',
        ]);
        $ad->categories()->attach($category->id, ['is_primary' => true, 'assigned_by' => $user->id]);

        $response = $this->postJson("/api/ads/{$ad->id}/update", [
            'slug' => 'samand-2020-updated',
            'status' => 'published',
            'status_note' => 'Approved by moderator',
            'status_metadata' => ['moderator' => 'system'],
        ]);

        $response->assertOk()
            ->assertJsonPath('data.slug', 'samand-2020-updated');

        $this->assertDatabaseHas('ad_slug_histories', [
            'ad_id' => $ad->id,
            'slug' => 'samand-2020',
            'redirect_to_slug' => 'samand-2020-updated',
        ]);

        $this->assertDatabaseHas('ad_status_histories', [
            'ad_id' => $ad->id,
            'from_status' => 'draft',
            'to_status' => 'published',
        ]);
    }
}
