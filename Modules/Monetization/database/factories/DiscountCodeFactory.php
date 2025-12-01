<?php

namespace Modules\Monetization\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

/**
 * @extends Factory<DiscountCode>
 */
class DiscountCodeFactory extends Factory
{
    protected $model = DiscountCode::class;

    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'plan_price_override_id' => PlanPriceOverride::factory(),
            'code' => strtoupper($this->faker->bothify('CODE###')),
            'description' => $this->faker->sentence(),
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(7),
            'usage_cap' => 25,
            'usage_count' => 0,
            'per_user_cap' => 2,
            'is_stackable' => false,
            'metadata' => ['channel' => 'factory'],
        ];
    }

    public function active(): self
    {
        return $this->state(fn () => [
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->addDays(5),
        ]);
    }
}
