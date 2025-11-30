<?php

namespace Modules\Monetization\Database\Factories;

use Modules\Monetization\Domain\Entities\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'price' => 10000,
            'currency' => 'IRR',
            'duration_days' => 7,
            'features' => ['highlight' => true],
            'price_overrides' => [],
            'active' => true,
            'order_column' => 1,
            'bump_cooldown_minutes' => null,
        ];
    }
}
