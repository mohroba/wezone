<?php

namespace Modules\Monetization\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\Contracts\PaymentGatewayInterface;
use Modules\Monetization\Domain\DTO\VerifyPaymentDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Repositories\EloquentPaymentRepository;
use Modules\Monetization\Domain\Repositories\EloquentPurchaseRepository;
use Modules\Monetization\Domain\Services\ApplyBump;
use Modules\Monetization\Domain\Services\PaymentGatewayRegistry;
use Modules\Monetization\Domain\Services\PurchaseActivationService;
use Modules\Monetization\Domain\Services\VerifyPaymentCallback;
use Tests\TestCase;

class VerifyPaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_activates_all_purchases_and_applies_bump(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        $plan = Plan::factory()->create([
            'price' => 5000,
            'currency' => 'IRR',
            'features' => ['bump' => ['allowance' => 1]],
        ]);

        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create([
                'payment_status' => 'pending',
                'payment_gateway' => 'stubpay',
                'amount' => 5000,
                'currency' => 'IRR',
                'bump_allowance' => 1,
            ]);

        $payment = Payment::factory()
            ->for($user, 'user')
            ->create([
                'status' => 'pending',
                'gateway' => 'stubpay',
                'ref_id' => 'REF-100',
                'payable_type' => AdPlanPurchase::class,
                'payable_id' => $purchase->id,
                'amount' => 5000,
                'currency' => 'IRR',
                'meta' => ['purchase_ids' => [$purchase->id]],
            ]);

        $gateway = new class implements PaymentGatewayInterface {
            public function getName(): string
            {
                return 'stubpay';
            }

            public function initiate(\Modules\Monetization\Domain\DTO\GatewayInitiationData $data): Payment
            {
                return $data->purchase->payments()->first();
            }

            public function verify(\Modules\Monetization\Domain\DTO\GatewayVerificationData $data): Payment
            {
                $data->payment->fill([
                    'status' => 'paid',
                    'paid_at' => now(),
                ])->save();

                return $data->payment;
            }

            public function refund(\Modules\Monetization\Domain\DTO\GatewayRefundData $data): Payment
            {
                return $data->payment;
            }
        };

        $registry = new class($gateway) extends PaymentGatewayRegistry {
            public function __construct(private PaymentGatewayInterface $gateway)
            {
            }

            public function resolve(?string $name = null): PaymentGatewayInterface
            {
                return $this->gateway;
            }
        };

        $paymentRepository = new EloquentPaymentRepository();
        $purchaseRepository = new EloquentPurchaseRepository();
        $activation = new PurchaseActivationService($purchaseRepository);
        $applyBump = new ApplyBump($purchaseRepository);

        $service = new VerifyPaymentCallback(
            paymentRepository: $paymentRepository,
            purchaseActivationService: $activation,
            gatewayRegistry: $registry,
            purchaseRepository: $purchaseRepository,
            applyBump: $applyBump,
        );

        $service(new VerifyPaymentDTO(
            gateway: 'stubpay',
            payload: ['authority' => 'REF-100'],
            idempotencyKey: null,
            correlationId: null,
        ));

        $purchase->refresh();

        $this->assertSame('active', $purchase->payment_status);
        $this->assertSame(0, $purchase->bump_allowance);
        $this->assertNotEmpty($purchase->meta['last_bumped_at'] ?? null);
        $this->assertNotNull($purchase->starts_at);
    }
}
