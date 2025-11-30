<?php

namespace Modules\Monetization\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\AdvertisableType;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Repositories\EloquentPlanRepository;
use Tests\TestCase;

class PlanRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_eager_loads_price_overrides(): void
    {
        $repository = new EloquentPlanRepository();

        $plan = Plan::factory()->create(['bump_cooldown_minutes' => 15]);
        $advertisableType = AdvertisableType::factory()->create();

        PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->state([
                'advertisable_type_id' => $advertisableType->id,
                'override_price' => 8000,
                'discount_type' => 'percent',
                'discount_value' => 12.5,
                'usage_cap' => 5,
            ])
            ->create();

        $found = $repository->findById($plan->id);

        $this->assertNotNull($found);
        $this->assertCount(1, $found->priceOverrides);
        $override = $found->priceOverrides->first();
        $this->assertSame('percent', $override->discount_type);
        $this->assertSame(12.5, $override->discount_value);
        $this->assertSame(5, $override->usage_cap);
    }
}
