<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Tests\Support\RefreshesAdDatabase;
use Modules\Auth\Models\Profile;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Entities\Plan;
use Tests\TestCase;

class AdControllerTest extends TestCase
{
    use RefreshesAdDatabase;

    public function test_store_creates_ad_and_nested_advertisable(): void
    {
        $user = User::factory()->create();
        $context = $this->prepareAdvertisableContext(AdCar::class);

        $payload = [
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'slug' => 'sporty-coupe',
            'title' => 'Sporty coupe for sale',
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Red'],
            ],
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
            'advertisable_type_id' => $context['type']->id,
        ]);

        $car = AdCar::query()->first();
        $this->assertNotNull($car);
        $this->assertSame('sporty-coupe', $car->slug);
        $this->assertSame(15000, (int) $car->mileage);
    }

    public function test_store_requires_exchange_details_when_exchangeable(): void
    {
        $user = User::factory()->create();
        $context = $this->prepareAdvertisableContext(AdCar::class);

        $payload = [
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'slug' => 'exchange-ready',
            'title' => 'Ready to exchange',
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => [
                    'brand_id' => 5,
                    'model_id' => 9,
                    'year' => 2023,
                    'mileage' => 15000,
                ],
            ],
            'is_exchangeable' => true,
        ];

        $response = $this->postJson('/api/ads', $payload);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['extra_amount', 'exchange_description']);
    }

    public function test_store_persists_exchange_configuration_flags(): void
    {
        $user = User::factory()->create();
        $context = $this->prepareAdvertisableContext(AdCar::class);

        $payload = [
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'slug' => 'configurable-exchange',
            'title' => 'Exchange with extras',
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => [
                    'brand_id' => 5,
                    'model_id' => 9,
                    'year' => 2023,
                    'mileage' => 15000,
                ],
            ],
            'is_exchangeable' => true,
            'comment_enable' => false,
            'phone_enable' => false,
            'chat_enable' => true,
            'extra_amount' => 120000,
            'exchange_description' => 'Newer sedan plus cash.',
        ];

        $response = $this->postJson('/api/ads', $payload);

        $response->assertCreated();
        $response->assertJsonPath('data.comment_enable', false);
        $response->assertJsonPath('data.phone_enable', false);
        $response->assertJsonPath('data.chat_enable', true);
        $response->assertJsonPath('data.extra_amount', 120000);
        $response->assertJsonPath('data.exchange_description', 'Newer sedan plus cash.');

        $this->assertDatabaseHas('ads', [
            'slug' => 'configurable-exchange',
            'comment_enable' => false,
            'phone_enable' => false,
            'chat_enable' => true,
            'extra_amount' => 120000,
            'exchange_description' => 'Newer sedan plus cash.',
        ]);
    }

    public function test_update_mutates_nested_advertisable_and_synchronises_slug(): void
    {
        $user = User::factory()->create();
        $context = $this->prepareAdvertisableContext(AdCar::class);
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
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => $adSlug,
            'title' => 'Reliable hatchback',
        ]);

        $payload = [
            'advertisable_type_id' => $context['type']->id,
            'slug' => 'city-hatchback-updated',
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Blue'],
            ],
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => [
                    'brand_id' => 1,
                    'model_id' => 2,
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
        $context = $this->prepareAdvertisableContext(AdJob::class);

        $payload = [
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'slug' => 'remote-role',
            'title' => 'Remote role',
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Hybrid'],
            ],
            'advertisable' => [
                'type' => AdJob::class,
                'attributes' => [
                    'company_name' => 'Acme Corp',
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
        $context = $this->prepareAdvertisableContext(AdCar::class);
        $ad = Ad::factory()->create([
            'view_count' => 2,
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => $context['type']->model_class,
        ]);

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
        $context = $this->prepareAdvertisableContext(AdCar::class);
        $ad = Ad::factory()->create([
            'view_count' => 0,
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => $context['type']->model_class,
        ]);

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

    public function test_show_endpoint_includes_creator_and_payments(): void
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        $follower = User::factory()->create();
        $user->followers()->attach($follower->id);

        $ad = Ad::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $plan = Plan::factory()->create([
            'price' => 1200,
            'currency' => 'IRR',
        ]);

        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create([
                'amount' => 1200,
                'currency' => 'IRR',
                'payment_gateway' => 'payping',
                'payment_status' => 'active',
            ]);

        $payment = Payment::factory()
            ->for($user, 'user')
            ->state([
                'payable_type' => AdPlanPurchase::class,
                'payable_id' => $purchase->id,
                'user_id' => $user->id,
                'gateway' => 'payping',
                'ref_id' => 'REF-12345',
            ])
            ->create();

        $response = $this->getJson("/api/ads/{$ad->id}");

        $response->assertOk();
        $response->assertJsonPath('data.creator.full_name', 'Jane Doe');
        $response->assertJsonPath('data.creator.ads_count', 1);
        $response->assertJsonPath('data.creator.followers_count', 1);
        $response->assertJsonPath('data.payments.0.id', $payment->id);
        $response->assertJsonPath('data.payments.0.gateway', 'payping');
        $response->assertJsonPath('data.monetization.active_promotions_count', 1);
        $response->assertJsonPath('data.monetization.purchases.0.id', $purchase->id);
        $response->assertJsonPath('data.monetization.purchases.0.plan.id', $plan->id);
        $response->assertJsonPath('data.monetization.purchases.0.payments.0.id', $payment->id);
    }

    private function prepareAdvertisableContext(string $modelClass): array
    {
        $faker = fake();

        $type = AdvertisableType::factory()->create([
            'key' => $faker->unique()->slug(),
            'label' => 'Test Type',
            'model_class' => $modelClass,
        ]);

        $category = AdCategory::create([
            'slug' => $faker->unique()->slug(),
            'name' => 'Root',
            'advertisable_type_id' => $type->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $group = AdAttributeGroup::create([
            'advertisable_type_id' => $type->id,
            'name' => 'Specs',
            'display_order' => 1,
        ]);

        $definition = AdAttributeDefinition::create([
            'attribute_group_id' => $group->id,
            'key' => 'test_attribute_'.$faker->unique()->numberBetween(1, 999),
            'label' => 'Test Attribute',
            'data_type' => 'string',
            'is_required' => false,
            'is_filterable' => false,
            'is_searchable' => false,
        ]);

        return compact('type', 'category', 'group', 'definition');
    }
}
