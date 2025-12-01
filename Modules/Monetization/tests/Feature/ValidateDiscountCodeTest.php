<?php

namespace Modules\Monetization\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Database\Factories\AdvertisableTypeFactory;
use Modules\Ad\Models\AdCategory;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Tests\TestCase;

class ValidateDiscountCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_discount_code_can_be_validated_for_plan_context(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $category = AdCategory::factory()->for($advertisableType, 'advertisableType')->create();
        $plan = Plan::factory()->create(['price' => 12000, 'currency' => 'IRR']);

        $override = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'ad_category_id' => $category->id,
                'override_price' => 10000,
                'discount_type' => 'percent',
                'discount_value' => 20,
                'metadata' => ['discount_codes' => ['LOYALTY20']],
            ])
            ->create();

        $response = $this->postJson(route('monetization.discount-codes.validate'), [
            'plan_id' => $plan->id,
            'advertisable_type_id' => $advertisableType->id,
            'ad_category_id' => $category->id,
            'discount_code' => 'LOYALTY20',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.price_rule_id', $override->id);
        $response->assertJsonPath('data.discount_applied', true);
        $response->assertJsonPath('data.discounted_price', 8000);
    }

    public function test_ineligible_user_cannot_apply_restricted_discount_code(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $plan = Plan::factory()->create(['price' => 15000, 'currency' => 'IRR']);

        PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'override_price' => 15000,
                'discount_type' => 'fixed',
                'discount_value' => 2000,
                'metadata' => [
                    'discount_codes' => ['VIPONLY'],
                    'eligible_user_ids' => [999],
                ],
            ])
            ->create();

        $response = $this->postJson(route('monetization.discount-codes.validate'), [
            'plan_id' => $plan->id,
            'advertisable_type_id' => $advertisableType->id,
            'discount_code' => 'VIPONLY',
        ]);

        $response->assertStatus(422);
        $response->assertJsonMissingPath('data');
    }
}
