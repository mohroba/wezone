<?php

namespace Modules\Monetization\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdvertisableType;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Services\PurchasePricingService;
use Tests\TestCase;

class PurchasePricingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_price_override_and_discount_are_applied_when_category_and_code_match(): void
    {
        $advertisableType = AdvertisableType::factory()->create();
        $category = AdCategory::factory()->for($advertisableType, 'advertisableType')->create();
        $plan = Plan::factory()->create(['price' => 12000, 'currency' => 'IRR']);

        $rule = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'ad_category_id' => $category->id,
                'override_price' => 10000,
                'discount_type' => 'percent',
                'discount_value' => 10,
                'metadata' => ['discount_codes' => ['SPRING24']],
            ])
            ->create();

        $service = new PurchasePricingService();

        $result = $service->calculate($plan, $advertisableType->id, $category->id, 'SPRING24');

        $this->assertSame(10000.0, $result->listPrice);
        $this->assertSame(9000.0, $result->discountedPrice);
        $this->assertTrue($result->discountApplied);
        $this->assertTrue($rule->is($result->priceRule));
    }

    public function test_plan_price_is_used_when_no_rule_matches(): void
    {
        $advertisableType = AdvertisableType::factory()->create();
        $plan = Plan::factory()->create(['price' => 15000, 'currency' => 'IRR']);

        $service = new PurchasePricingService();

        $result = $service->calculate($plan, $advertisableType->id + 1, null, null);

        $this->assertSame(15000.0, $result->listPrice);
        $this->assertSame(15000.0, $result->discountedPrice);
        $this->assertFalse($result->discountApplied);
        $this->assertNull($result->priceRule);
    }

    public function test_discount_is_skipped_when_code_is_missing(): void
    {
        $advertisableType = AdvertisableType::factory()->create();
        $plan = Plan::factory()->create(['price' => 20000, 'currency' => 'IRR']);

        PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'override_price' => 18000,
                'discount_type' => 'fixed',
                'discount_value' => 2000,
                'metadata' => ['discount_codes' => ['VIPONLY']],
            ])
            ->create();

        $service = new PurchasePricingService();

        $withoutCode = $service->calculate($plan, $advertisableType->id, null, null);
        $this->assertSame(18000.0, $withoutCode->listPrice);
        $this->assertSame(18000.0, $withoutCode->discountedPrice);
        $this->assertFalse($withoutCode->discountApplied);

        $withCode = $service->calculate($plan->fresh(), $advertisableType->id, null, 'VIPONLY');
        $this->assertSame(16000.0, $withCode->discountedPrice);
        $this->assertTrue($withCode->discountApplied);
    }

    public function test_discount_is_limited_to_specific_users_when_configured(): void
    {
        $advertisableType = AdvertisableType::factory()->create();
        $plan = Plan::factory()->create(['price' => 30000, 'currency' => 'IRR']);

        PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'override_price' => 25000,
                'discount_type' => 'percent',
                'discount_value' => 20,
                'metadata' => [
                    'discount_codes' => ['LOYALTY20'],
                    'eligible_user_ids' => [42, 99],
                ],
            ])
            ->create();

        $service = new PurchasePricingService();

        $ineligible = $service->calculate($plan, $advertisableType->id, null, 'LOYALTY20', userId: 7);
        $this->assertSame(25000.0, $ineligible->discountedPrice);
        $this->assertFalse($ineligible->discountApplied);

        $eligible = $service->calculate($plan->fresh(), $advertisableType->id, null, 'LOYALTY20', userId: 42);
        $this->assertSame(20000.0, $eligible->discountedPrice);
        $this->assertTrue($eligible->discountApplied);
    }
}
