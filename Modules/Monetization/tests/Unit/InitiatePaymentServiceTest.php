<?php

namespace Modules\Monetization\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Modules\Ad\Database\Factories\AdFactory;
use Modules\Ad\Database\Factories\AdvertisableTypeFactory;
use Modules\Monetization\Domain\Contracts\PaymentGatewayInterface;
use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\DTO\GatewayRefundData;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Repositories\EloquentPaymentRepository;
use Modules\Monetization\Domain\Repositories\EloquentPlanRepository;
use Modules\Monetization\Domain\Repositories\EloquentPurchaseRepository;
use Modules\Monetization\Domain\Services\InitiatePayment;
use Modules\Monetization\Domain\Services\PaymentGatewayRegistry;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Tests\TestCase;

class InitiatePaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_uses_discounted_amount_and_records_redemption_meta(): void
    {
        Config::set('monetization.default_gateway', 'fake');

        $user = User::factory()->create();
        $advertisableType = AdvertisableTypeFactory::new()->create();
        $plan = Plan::factory()->create(['price' => 12000, 'currency' => 'IRR']);
        $ad = AdFactory::new()->for($user)->state(['advertisable_type_id' => $advertisableType->id])->create();

        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create([
                'amount' => 12000,
                'list_price' => 12000,
                'discounted_price' => 8000,
                'currency' => 'IRR',
                'payment_status' => 'draft',
                'meta' => [
                    'pricing' => [
                        'list_price' => 12000,
                        'discounted_price' => 8000,
                        'currency' => 'IRR',
                        'discount_applied' => true,
                        'discount_code' => 'SAVE2000',
                    ],
                    'discount_redemption' => [
                        'discount_code' => 'SAVE2000',
                        'discounted_price' => 8000,
                    ],
                ],
            ]);

        $gateway = new class implements PaymentGatewayInterface {
            public ?GatewayInitiationData $lastData = null;

            public function getName(): string
            {
                return 'fake';
            }

            public function initiate(GatewayInitiationData $data): Payment
            {
                $this->lastData = $data;
                $payment = $data->purchase->payments()->latest()->firstOrFail();
                $payment->update(['request_payload' => ['amount' => $data->money->amount()]]);

                return $payment;
            }

            public function verify(GatewayVerificationData $data): Payment
            {
                return $data->payment;
            }

            public function refund(GatewayRefundData $data): Payment
            {
                return $data->payment;
            }
        };

        Config::set('monetization.gateways.fake', ['driver' => get_class($gateway)]);
        app()->instance(get_class($gateway), $gateway);

        $gatewayRegistry = new PaymentGatewayRegistry(app());

        $service = new InitiatePayment(
            purchaseRepository: new EloquentPurchaseRepository(),
            planRepository: new EloquentPlanRepository(),
            paymentRepository: new EloquentPaymentRepository(),
            gatewayRegistry: $gatewayRegistry,
        );

        $payment = $service(new \Modules\Monetization\Domain\DTO\InitiatePaymentDTO(
            purchaseId: $purchase->id,
            userId: $user->id,
            gateway: 'fake',
            correlationId: null,
            idempotencyKey: null,
        ));

        $this->assertSame(8000.0, $payment->amount);
        $this->assertSame(8000.0, $payment->request_payload['amount']);
        $this->assertSame('fake', $payment->gateway);
        $this->assertSame(8000.0, $gateway->lastData?->money->amount());
        $this->assertSame('SAVE2000', $payment->meta['discount_redemption']['discount_code']);
        $this->assertSame(8000.0, $payment->meta['charged_amount']);
    }
}
