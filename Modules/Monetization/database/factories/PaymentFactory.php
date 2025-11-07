<?php

namespace Modules\Monetization\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $userFactory = User::factory();
        $purchaseFactory = AdPlanPurchase::factory()->for($userFactory, 'user');

        return [
            'user_id' => $userFactory,
            'payable_type' => AdPlanPurchase::class,
            'payable_id' => $purchaseFactory,
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'currency' => 'IRR',
            'gateway' => $this->faker->randomElement(['payping', 'idpay', 'zarinpal', 'stripe']),
            'status' => 'pending',
            'ref_id' => null,
            'tracking_code' => null,
            'request_payload' => [],
            'response_payload' => [],
            'paid_at' => null,
            'failed_at' => null,
            'refunded_at' => null,
            'correlation_id' => $this->faker->uuid(),
            'idempotency_key' => null,
        ];
    }

    public function paid(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
                'paid_at' => now(),
            ];
        });
    }
}
