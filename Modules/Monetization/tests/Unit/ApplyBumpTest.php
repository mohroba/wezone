<?php

namespace Modules\Monetization\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\Models\User;
use Modules\Ad\Database\Factories\AdFactory;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Repositories\EloquentPurchaseRepository;
use Modules\Monetization\Domain\Services\ApplyBump;
use Tests\TestCase;

class ApplyBumpTest extends TestCase
{
    use RefreshDatabase;

    public function test_bump_respects_purchase_level_cooldown(): void
    {
        $plan = Plan::factory()->create(['bump_cooldown_minutes' => 90]);
        $user = User::factory()->create();
        $ad = AdFactory::new()->for($user)->create();

        $purchase = AdPlanPurchase::factory()
            ->for($plan, 'plan')
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->state([
                'payment_status' => 'active',
                'bump_allowance' => 2,
                'bump_cooldown_minutes' => 15,
                'meta' => ['last_bumped_at' => now()->subMinutes(10)->toIso8601String()],
            ])
            ->create();

        $service = new ApplyBump(new EloquentPurchaseRepository());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bump cooldown of 15 minutes has not elapsed.');

        $service($purchase);
    }

    public function test_bump_uses_plan_cooldown_when_purchase_value_missing(): void
    {
        $plan = Plan::factory()->create(['bump_cooldown_minutes' => 45]);
        $user = User::factory()->create();
        $ad = AdFactory::new()->for($user)->create();

        $purchase = AdPlanPurchase::factory()
            ->for($plan, 'plan')
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->state([
                'payment_status' => 'active',
                'bump_allowance' => 1,
                'bump_cooldown_minutes' => null,
                'meta' => ['last_bumped_at' => now()->subMinutes(30)->toIso8601String()],
            ])
            ->create();

        $service = new ApplyBump(new EloquentPurchaseRepository());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bump cooldown of 45 minutes has not elapsed.');

        $service($purchase);
    }

    public function test_bump_falls_back_to_configured_cooldown(): void
    {
        Config::set('monetization.features.bump.cooldown_minutes', 5);
        $plan = Plan::factory()->create(['bump_cooldown_minutes' => null]);
        $user = User::factory()->create();
        $ad = AdFactory::new()->for($user)->create();

        $purchase = AdPlanPurchase::factory()
            ->for($plan, 'plan')
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->state([
                'payment_status' => 'active',
                'bump_allowance' => 1,
                'bump_cooldown_minutes' => null,
                'meta' => ['last_bumped_at' => now()->subMinutes(4)->toIso8601String()],
            ])
            ->create();

        $service = new ApplyBump(new EloquentPurchaseRepository());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bump cooldown of 5 minutes has not elapsed.');

        $service($purchase);
    }

    public function test_bump_uses_plan_feature_cooldown_and_updates_metadata(): void
    {
        $plan = Plan::factory()->create([
            'bump_cooldown_minutes' => null,
            'features' => [
                'bump' => [
                    'allowance' => 3,
                    'cooldown_minutes' => 10,
                ],
            ],
        ]);
        $user = User::factory()->create();
        $ad = AdFactory::new()->for($user)->create();

        $purchase = AdPlanPurchase::factory()
            ->for($plan, 'plan')
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->state([
                'payment_status' => 'active',
                'bump_allowance' => 3,
                'bump_cooldown_minutes' => null,
                'meta' => ['last_bumped_at' => now()->subMinutes(15)->toIso8601String()],
            ])
            ->create();

        $service = new ApplyBump(new EloquentPurchaseRepository());

        $updated = $service($purchase);

        $this->assertSame(2, $updated->bump_allowance);
        $this->assertTrue(
            Carbon::parse($updated->meta['last_bumped_at'])->greaterThanOrEqualTo(now()->subSeconds(5))
        );
    }
}
