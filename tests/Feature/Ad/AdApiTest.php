<?php

namespace Tests\Feature\Ad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Services\CategoryHierarchyManager;
use Modules\Ad\Support\AdvertisableType;
use Tests\TestCase;

class AdApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_ads_with_filters(): void
    {
        $ads = Ad::factory()->count(3)->create();

        $response = $this->getJson('/api/ads');
        $response->assertOk()
            ->assertJsonPath('meta.total', $ads->count());

        $published = $ads->firstWhere('status', 'published');
        if ($published) {
            $filtered = $this->getJson('/api/ads?status=published');
            $filtered->assertOk()
                ->assertJsonCount(Ad::query()->where('status', 'published')->count(), 'data');
        }
    }

    public function test_it_creates_an_ad_with_categories(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory();

        $payload = [
            'user_id' => $user->id,
            'advertisable' => [
                'type' => AdvertisableType::allowed()[0],
                'attributes' => [
                    'brand_id' => 10,
                    'model_id' => 25,
                    'year' => 2024,
                    'mileage' => 15000,
                    'fuel_type' => 'بنزین',
                    'transmission' => 'اتوماتیک',
                ],
            ],
            'slug' => 'forosh-peugeot-jadid',
            'title' => 'فروش ویژه پژو ۲۰۷',
            'description' => 'این خودرو بسیار تمیز و کم‌کارکرد است.',
            'status' => 'draft',
            'price_amount' => 520000000,
            'price_currency' => 'IRR',
            'is_negotiable' => true,
            'contact_channel' => ['تلفن' => '۰۹۱۲۱۲۳۴۵۶۷'],
            'categories' => [
                ['id' => $category->id, 'is_primary' => true, 'assigned_by' => $user->id],
            ],
        ];

        $response = $this->postJson('/api/ads', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.slug', 'forosh-peugeot-jadid')
            ->assertJsonPath('data.categories.0.id', $category->id);

        $this->assertDatabaseHas('ads', [
            'slug' => 'forosh-peugeot-jadid',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('ad_category_ad', [
            'category_id' => $category->id,
            'is_primary' => true,
        ]);
    }

    public function test_it_shows_a_single_ad(): void
    {
        $ad = Ad::factory()->create([
            'title' => 'آگهی تستی ویژه',
        ]);

        $response = $this->getJson('/api/ads/' . $ad->id);

        $response->assertOk()
            ->assertJsonPath('data.id', $ad->id)
            ->assertJsonPath('data.title', 'آگهی تستی ویژه');
    }

    public function test_it_returns_real_world_persian_ad_details(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory();

        $ad = Ad::factory()->create([
            'user_id' => $user->id,
            'slug' => 'forosh-peugeot-pars',
            'title' => 'فروش پژو پارس سفارشی',
            'description' => 'خودرو بدون رنگ با سرویس‌های منظم و لاستیک‌های نو.',
            'status' => 'published',
            'contact_channel' => [
                'تلفن' => '۰۲۱۲۲۳۳۴۴۵۵',
                'واتساپ' => '۰۹۱۲۳۴۵۶۷۸۹',
            ],
        ]);

        $ad->categories()->sync([
            $category->id => ['is_primary' => true, 'assigned_by' => $user->id],
        ]);

        $response = $this->getJson('/api/ads/' . $ad->id);

        $response->assertOk()
            ->assertJsonPath('data.slug', 'forosh-peugeot-pars')
            ->assertJsonPath('data.title', 'فروش پژو پارس سفارشی')
            ->assertJsonPath('data.description', 'خودرو بدون رنگ با سرویس‌های منظم و لاستیک‌های نو.')
            ->assertJsonPath('data.contact_channel.واتساپ', '۰۹۱۲۳۴۵۶۷۸۹')
            ->assertJsonPath('data.categories.0.name', 'دسته اصلی');
    }

    public function test_it_updates_ad_and_records_status_and_slug_history(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory();

        $ad = Ad::factory()->create([
            'user_id' => $user->id,
            'slug' => 'agahi-ghadimi',
            'title' => 'آگهی قدیمی',
            'status' => 'draft',
        ]);
        $ad->categories()->sync([$category->id => ['is_primary' => true, 'assigned_by' => $user->id]]);

        $response = $this->postJson("/api/ads/{$ad->id}/update", [
            'slug' => 'agahi-be-roz',
            'status' => 'published',
            'status_note' => 'تأیید مدیر',
            'status_metadata' => ['توضیح' => 'به روز رسانی کامل'],
        ]);

        $response->assertOk()
            ->assertJsonPath('data.slug', 'agahi-be-roz')
            ->assertJsonPath('data.status', 'published');

        $this->assertDatabaseHas('ad_slug_histories', [
            'ad_id' => $ad->id,
            'slug' => 'agahi-ghadimi',
            'redirect_to_slug' => 'agahi-be-roz',
        ]);

        $this->assertDatabaseHas('ad_status_histories', [
            'ad_id' => $ad->id,
            'from_status' => 'draft',
            'to_status' => 'published',
        ]);
    }

    public function test_it_soft_deletes_an_ad(): void
    {
        $ad = Ad::factory()->create();

        $response = $this->postJson("/api/ads/{$ad->id}/delete");
        $response->assertNoContent();

        $this->assertSoftDeleted('ads', ['id' => $ad->id]);
    }

    private function createCategory(): AdCategory
    {
        $category = AdCategory::factory()->create([
            'slug' => 'daste-asli',
            'name' => 'دسته اصلی',
        ]);

        app(CategoryHierarchyManager::class)->handleCreated($category);

        return $category->fresh();
    }
}
