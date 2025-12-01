<?php

namespace Modules\Monetization\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\DiscountRedemption;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Entities\Plan;
use App\Models\User;

/**
 * @extends Factory<DiscountRedemption>
 */
class DiscountRedemptionFactory extends Factory
{
    protected $model = DiscountRedemption::class;

    public function definition(): array
    {
        return [
            'discount_code_id' => DiscountCode::factory(),
            'plan_price_override_id' => PlanPriceOverride::factory()->for(Plan::factory(), 'plan'),
            'ad_plan_purchase_id' => AdPlanPurchase::factory(),
            'user_id' => User::factory(),
            'amount_before' => 10000,
            'amount_after' => 8000,
            'discount_amount' => 2000,
            'redeemed_at' => now(),
            'meta' => ['source' => 'factory'],
        ];
    }
}
