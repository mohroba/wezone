<?php

namespace Modules\Monetization\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Database\Factories\AdvertisableTypeFactory;
use Modules\Ad\Models\AdCategory;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\DiscountRedemption;
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

        DiscountCode::factory()
            ->for($plan, 'plan')
            ->for($override, 'priceRule')
            ->state(['code' => 'LOYALTY20'])
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

        $restrictedRule = PlanPriceOverride::factory()
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

        DiscountCode::factory()
            ->for($plan, 'plan')
            ->for($restrictedRule, 'priceRule')
            ->state(['code' => 'VIPONLY'])
            ->create();

        $response = $this->postJson(route('monetization.discount-codes.validate'), [
            'plan_id' => $plan->id,
            'advertisable_type_id' => $advertisableType->id,
            'discount_code' => 'VIPONLY',
        ]);

        $response->assertStatus(422);
        $response->assertJsonMissingPath('data');
    }

    public function test_discount_code_is_rejected_outside_usage_window(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $plan = Plan::factory()->create(['price' => 9000, 'currency' => 'IRR']);

        $futureRule = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'override_price' => 9000,
                'discount_type' => 'percent',
                'discount_value' => 10,
                'discount_starts_at' => now()->addDay(),
                'metadata' => ['discount_codes' => ['FUTURE10']],
            ])
            ->create();

        DiscountCode::factory()
            ->for($plan, 'plan')
            ->for($futureRule, 'priceRule')
            ->state([
                'code' => 'FUTURE10',
                'starts_at' => now()->addDay(),
            ])
            ->create();

        $response = $this->postJson(route('monetization.discount-codes.validate'), [
            'plan_id' => $plan->id,
            'advertisable_type_id' => $advertisableType->id,
            'discount_code' => 'FUTURE10',
        ]);

        $response->assertStatus(422);
        $response->assertJsonMissingPath('data');
    }

    public function test_discount_code_requires_matching_category_context(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $matchedCategory = AdCategory::factory()->for($advertisableType, 'advertisableType')->create();
        $otherCategory = AdCategory::factory()->for($advertisableType, 'advertisableType')->create();
        $plan = Plan::factory()->create(['price' => 12000, 'currency' => 'IRR']);

        $categoryRule = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'ad_category_id' => $matchedCategory->id,
                'override_price' => 12000,
                'discount_type' => 'percent',
                'discount_value' => 15,
                'metadata' => ['discount_codes' => ['CAT15']],
            ])
            ->create();

        DiscountCode::factory()
            ->for($plan, 'plan')
            ->for($categoryRule, 'priceRule')
            ->state(['code' => 'CAT15'])
            ->create();

        $response = $this->postJson(route('monetization.discount-codes.validate'), [
            'plan_id' => $plan->id,
            'advertisable_type_id' => $advertisableType->id,
            'ad_category_id' => $otherCategory->id,
            'discount_code' => 'CAT15',
        ]);

        $response->assertStatus(422);
        $response->assertJsonMissingPath('data');
    }

    public function test_discount_code_respects_per_user_cap(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $plan = Plan::factory()->create(['price' => 5000, 'currency' => 'IRR']);

        $priceRule = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'override_price' => 5000,
                'discount_type' => 'fixed',
                'discount_value' => 500,
                'metadata' => ['discount_codes' => ['ONCEONLY']],
            ])
            ->create();

        $discountCode = DiscountCode::factory()
            ->for($plan, 'plan')
            ->for($priceRule, 'priceRule')
            ->state([
                'code' => 'ONCEONLY',
                'per_user_cap' => 1,
            ])
            ->create();

        DiscountRedemption::factory()
            ->for($discountCode, 'discountCode')
            ->for($priceRule, 'priceRule')
            ->for(AdPlanPurchase::factory()->for($plan, 'plan'), 'purchase')
            ->for($user, 'user')
            ->create();

        $response = $this->postJson(route('monetization.discount-codes.validate'), [
            'plan_id' => $plan->id,
            'advertisable_type_id' => $advertisableType->id,
            'discount_code' => 'ONCEONLY',
        ]);

        $response->assertStatus(422);
        $response->assertJsonMissingPath('data');
    }
}
