<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\DTO\GatewayRefundData;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Gateways\PayPingGateway;
use Modules\Monetization\Domain\ValueObjects\Money;
use Tests\TestCase;

class PayPingGatewayTest extends TestCase
{
    use RefreshDatabase {
        migrateDatabases as baseMigrateDatabases;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        config()->set('monetization.gateways.payping', [
            'driver' => PayPingGateway::class,
            'api_token' => 'test-token',
            'callback_url' => 'https://example.com/callback',
            'sandbox' => false,
            'timeout' => 5,
        ]);
        config()->set('monetization.default_gateway', 'payping');
    }

    protected function migrateDatabases(): void
    {
        $this->baseMigrateDatabases();

        $this->artisan('migrate', [
            '--path' => module_path('Ad', 'database/migrations'),
            '--realpath' => true,
            '--force' => true,
        ]);

        $this->artisan('migrate', [
            '--path' => module_path('Monetization', 'database/migrations'),
            '--realpath' => true,
            '--force' => true,
        ]);
    }

    public function test_it_initiates_payment_and_stores_reference(): void
    {
        Http::fake([
            'https://api.payping.ir/v2/payments' => Http::response(['code' => 'ABC123'], 200),
        ]);

        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);

        $purchase = AdPlanPurchase::query()->create([
            'ad_id' => $ad->id,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'payment_status' => 'draft',
        ]);

        $payment = new Payment([
            'user_id' => $user->id,
            'amount' => $purchase->amount,
            'currency' => $purchase->currency,
            'gateway' => 'payping',
            'status' => 'pending',
        ]);
        $payment->payable()->associate($purchase);
        $payment->save();

        $gateway = new PayPingGateway();
        $gateway->setConfig(config('monetization.gateways.payping'));

        $result = $gateway->initiate(new GatewayInitiationData(
            purchase: $purchase,
            plan: $plan,
            money: Money::fromFloat($purchase->amount, $purchase->currency),
            callbackUrl: config('monetization.gateways.payping.callback_url'),
        ));

        $payment->refresh();

        $this->assertSame('ABC123', $payment->ref_id);
        $this->assertSame([
            'amount' => (int) $purchase->amount,
            'clientRefId' => (string) $purchase->getKey(),
            'returnUrl' => 'https://example.com/callback',
            'description' => 'Plan purchase #'.$purchase->getKey(),
        ], $payment->request_payload);
        $this->assertSame(['code' => 'ABC123'], $payment->response_payload);
        $this->assertTrue($result->is($payment));

        Http::assertSent(function ($request) use ($purchase): bool {
            return $request->url() === 'https://api.payping.ir/v2/payments'
                && $request['clientRefId'] === (string) $purchase->getKey()
                && $request['amount'] === (int) round($purchase->amount)
                && $request['returnUrl'] === 'https://example.com/callback';
        });
    }

    public function test_it_verifies_payment_and_marks_as_paid(): void
    {
        Http::fake([
            'https://api.payping.ir/v2/payments/verify' => Http::response(['trackingCode' => 'TRK123'], 200),
        ]);

        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);

        $purchase = AdPlanPurchase::query()->create([
            'ad_id' => $ad->id,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'payment_status' => 'pending',
        ]);

        $payment = new Payment([
            'user_id' => $user->id,
            'amount' => $purchase->amount,
            'currency' => $purchase->currency,
            'gateway' => 'payping',
            'status' => 'pending',
            'ref_id' => 'ABC123',
        ]);
        $payment->payable()->associate($purchase);
        $payment->save();

        $gateway = new PayPingGateway();
        $gateway->setConfig(config('monetization.gateways.payping'));

        $result = $gateway->verify(new GatewayVerificationData(
            payment: $payment,
            payload: ['refId' => 'ABC123'],
        ));

        $payment->refresh();

        $this->assertTrue($result->is($payment));
        $this->assertSame('paid', $payment->status);
        $this->assertSame('TRK123', $payment->tracking_code);
        $this->assertEquals(['trackingCode' => 'TRK123'], $payment->response_payload);
        $this->assertNotNull($payment->paid_at);

        Http::assertSent(function ($request) use ($payment): bool {
            return $request->url() === 'https://api.payping.ir/v2/payments/verify'
                && $request['refId'] === $payment->ref_id
                && $request['amount'] === (int) round($payment->amount);
        });
    }

    public function test_it_marks_payment_as_refunded(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);

        $purchase = AdPlanPurchase::query()->create([
            'ad_id' => $ad->id,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'payment_status' => 'paid',
        ]);

        $payment = new Payment([
            'user_id' => $user->id,
            'amount' => $purchase->amount,
            'currency' => $purchase->currency,
            'gateway' => 'payping',
            'status' => 'paid',
            'ref_id' => 'ABC123',
        ]);
        $payment->payable()->associate($purchase);
        $payment->save();

        $gateway = new PayPingGateway();
        $gateway->setConfig(config('monetization.gateways.payping'));

        $gateway->refund(new GatewayRefundData($payment));

        $payment->refresh();

        $this->assertSame('refunded', $payment->status);
        $this->assertNotNull($payment->refunded_at);
    }
}
