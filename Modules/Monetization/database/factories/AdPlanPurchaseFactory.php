<?php

namespace Modules\Monetization\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Plan;

/**
 * @extends Factory<AdPlanPurchase>
 */
class AdPlanPurchaseFactory extends Factory
{
    protected $model = AdPlanPurchase::class;

    public function definition(): array
    {
        $userFactory = User::factory();

        return [
            'ad_id' => Ad::factory()->for($userFactory),
            'plan_id' => Plan::factory(),
            'user_id' => $userFactory,
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'currency' => 'IRR',
            'starts_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'ends_at' => $this->faker->dateTimeBetween('now', '+30 days'),
            'payment_status' => 'draft',
            'payment_gateway' => null,
            'meta' => [],
            'correlation_id' => $this->faker->uuid(),
            'idempotency_key' => null,
            'bump_allowance' => 0,
        ];
    }
}
