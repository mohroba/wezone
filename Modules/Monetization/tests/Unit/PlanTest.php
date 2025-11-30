<?php

namespace Modules\Monetization\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\AdvertisableType;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Tests\TestCase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_plan_defaults_include_empty_overrides_and_null_cooldown(): void
    {
        $plan = Plan::factory()->create();

        $this->assertSame([], $plan->price_overrides);
        $this->assertNull($plan->bump_cooldown_minutes);
    }

    public function test_plan_price_overrides_relation_returns_records(): void
    {
        $plan = Plan::factory()->create([
            'price_overrides' => [
                ['advertisable_type' => 'custom', 'ad_category_id' => null, 'price' => 12000],
            ],
            'bump_cooldown_minutes' => 45,
        ]);

        $advertisableType = AdvertisableType::factory()->create();

        $override = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->state([
                'advertisable_type_id' => $advertisableType->id,
                'override_price' => 9500,
                'discount_type' => 'fixed',
                'discount_value' => 500,
                'usage_cap' => 10,
            ])
            ->create();

        $plan->load('priceOverrides');

        $this->assertSame(45, $plan->bump_cooldown_minutes);
        $this->assertCount(1, $plan->priceOverrides);
        $this->assertTrue($plan->priceOverrides->first()->is($override));
        $this->assertSame('fixed', $plan->priceOverrides->first()->discount_type);
        $this->assertSame(10, $plan->priceOverrides->first()->usage_cap);
    }
}
