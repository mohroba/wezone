<?php

namespace Modules\Monetization\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\DTO\InitiatePaymentDTO;
use Modules\Monetization\Domain\DTO\VerifyPaymentDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Services\InitiatePayment;
use Modules\Monetization\Domain\Services\VerifyPaymentCallback;
use Modules\Monetization\Domain\Entities\Plan;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(User::factory()->create());
    }

    public function test_index_returns_only_authenticated_user_payments(): void
    {
        $user = auth()->user();
        $ownPayment = Payment::factory()->for($user, 'user')->create(['status' => 'paid']);
        Payment::factory()->create();

        $response = $this->getJson('/api/monetization/payments?status=paid');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $ownPayment->id);
    }

    public function test_store_initiates_payment_via_service(): void
    {
        $user = auth()->user();

        $plan = Plan::factory()->create(['price' => 3000, 'currency' => 'IRR']);
        $ad = Ad::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create(['amount' => 3000, 'currency' => 'IRR']);

        $payment = Payment::factory()
            ->for($user, 'user')
            ->state([
                'payable_type' => AdPlanPurchase::class,
                'payable_id' => $purchase->id,
                'user_id' => $user->id,
                'gateway' => 'payping',
                'ref_id' => 'SIM-REF',
            ])
            ->create();

        $stub = new class($payment) extends InitiatePayment {
            public function __construct(private Payment $payment)
            {
            }

            public function __invoke(InitiatePaymentDTO $dto): Payment
            {
                return $this->payment;
            }
        };

        $this->app->instance(InitiatePayment::class, $stub);

        $response = $this->postJson('/api/monetization/payments', [
            'purchase_id' => $purchase->id,
            'gateway' => 'payping',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.id', $payment->id);
        $this->assertNotEmpty($response->json('data.redirect_url'));
    }

    public function test_store_uses_default_gateway_when_none_provided(): void
    {
        Config::set('monetization.default_gateway', 'payping');

        $user = auth()->user();

        $plan = Plan::factory()->create(['price' => 3000, 'currency' => 'IRR']);
        $ad = Ad::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create([
                'amount' => 3000,
                'currency' => 'IRR',
                'payment_gateway' => null,
            ]);

        $payment = Payment::factory()
            ->for($user, 'user')
            ->state([
                'payable_type' => AdPlanPurchase::class,
                'payable_id' => $purchase->id,
                'gateway' => 'payping',
                'ref_id' => 'SIM-DEFAULT',
            ])
            ->create();

        $stub = new class($payment) extends InitiatePayment {
            public ?string $lastGateway = null;

            public function __construct(private Payment $payment)
            {
            }

            public function __invoke(InitiatePaymentDTO $dto): Payment
            {
                $this->lastGateway = $dto->gateway;

                return $this->payment;
            }
        };

        $this->app->instance(InitiatePayment::class, $stub);

        $response = $this->postJson('/api/monetization/payments', [
            'purchase_id' => $purchase->id,
        ]);

        $response->assertOk();
        $this->assertSame('payping', $stub->lastGateway);
    }

    public function test_validate_payment_uses_verification_service(): void
    {
        $user = auth()->user();

        $payment = Payment::factory()->for($user, 'user')->create([
            'status' => 'pending',
            'gateway' => 'payping',
            'ref_id' => 'VAL-123',
        ]);

        $stub = new class($payment) extends VerifyPaymentCallback {
            public function __construct(private Payment $payment)
            {
            }

            public function __invoke(VerifyPaymentDTO $dto): Payment
            {
                $this->payment->status = 'paid';
                $this->payment->paid_at = now();
                $this->payment->save();

                return $this->payment;
            }
        };

        $this->app->instance(VerifyPaymentCallback::class, $stub);

        $response = $this->postJson("/api/monetization/payments/{$payment->id}/validate", [
            'payload' => ['authority' => 'VAL-123'],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'paid');
    }

    public function test_initiate_uses_default_gateway_when_missing(): void
    {
        Config::set('monetization.default_gateway', 'payping');

        $user = auth()->user();

        $plan = Plan::factory()->create(['price' => 6000, 'currency' => 'IRR']);
        $ad = Ad::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create([
                'amount' => 6000,
                'currency' => 'IRR',
                'payment_gateway' => null,
            ]);

        $payment = Payment::factory()
            ->for($user, 'user')
            ->state([
                'payable_type' => AdPlanPurchase::class,
                'payable_id' => $purchase->id,
                'gateway' => 'payping',
                'ref_id' => 'SIM-INITIATE',
            ])
            ->create();

        $stub = new class($payment) extends InitiatePayment {
            public ?string $lastGateway = null;

            public function __construct(private Payment $payment)
            {
            }

            public function __invoke(InitiatePaymentDTO $dto): Payment
            {
                $this->lastGateway = $dto->gateway;

                return $this->payment;
            }
        };

        $this->app->instance(InitiatePayment::class, $stub);

        $response = $this->postJson("/api/monetization/purchases/{$purchase->id}/payments/initiate", []);

        $response->assertOk();
        $this->assertSame('payping', $stub->lastGateway);
    }

    public function test_ad_payments_requires_owner_access(): void
    {
        $user = auth()->user();
        $ad = Ad::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        $purchase = AdPlanPurchase::factory()->for($user, 'user')->for($ad, 'ad')->create();
        $payment = Payment::factory()->for($user, 'user')->state([
            'payable_type' => AdPlanPurchase::class,
            'payable_id' => $purchase->id,
        ])->create();

        $response = $this->getJson("/api/monetization/ads/{$ad->id}/payments");
        $response->assertOk();
        $response->assertJsonPath('data.0.id', $payment->id);

        Passport::actingAs(User::factory()->create());
        $this->getJson("/api/monetization/ads/{$ad->id}/payments")->assertForbidden();
    }
}
