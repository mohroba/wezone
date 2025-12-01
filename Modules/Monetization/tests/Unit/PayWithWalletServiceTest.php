<?php

namespace Modules\Monetization\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Database\Factories\AdFactory;
use Modules\Ad\Database\Factories\AdvertisableTypeFactory;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Repositories\EloquentPaymentRepository;
use Modules\Monetization\Domain\Repositories\EloquentPurchaseRepository;
use Modules\Monetization\Domain\Repositories\EloquentWalletRepository;
use Modules\Monetization\Domain\Services\PayWithWallet;
use Modules\Monetization\Domain\Services\PurchaseActivationService;
use Modules\Monetization\Domain\Entities\WalletTransaction;
use Tests\TestCase;

class PayWithWalletServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_payment_uses_discounted_amount_and_is_idempotent(): void
    {
        $user = User::factory()->create();
        $advertisableType = AdvertisableTypeFactory::new()->create();
        $plan = Plan::factory()->create(['price' => 15000, 'currency' => 'IRR', 'duration_days' => 7]);
        $ad = AdFactory::new()->for($user)->state(['advertisable_type_id' => $advertisableType->id])->create();

        $purchase = AdPlanPurchase::factory()
            ->for($user, 'user')
            ->for($ad, 'ad')
            ->for($plan, 'plan')
            ->create([
                'amount' => 15000,
                'list_price' => 15000,
                'discounted_price' => 10000,
                'currency' => 'IRR',
                'payment_status' => 'draft',
                'idempotency_key' => 'wallet-key',
                'meta' => [
                    'pricing' => [
                        'list_price' => 15000,
                        'discounted_price' => 10000,
                        'currency' => 'IRR',
                        'discount_applied' => true,
                        'discount_code' => 'SAVE5000',
                    ],
                    'discount_redemption' => [
                        'discount_code' => 'SAVE5000',
                        'discounted_price' => 10000,
                    ],
                ],
            ]);

        $walletRepository = new EloquentWalletRepository();
        $wallet = $walletRepository->findOrCreateForUser($user->id, 'IRR');
        $walletRepository->updateBalance($wallet, 20000);

        $service = new PayWithWallet(
            walletRepository: $walletRepository,
            paymentRepository: new EloquentPaymentRepository(),
            activationService: new PurchaseActivationService(new EloquentPurchaseRepository()),
        );

        $payment = $service($purchase);

        $wallet->refresh();
        $transaction = $wallet->transactions()->latest()->first();

        $this->assertSame(10000.0, $payment->amount);
        $this->assertSame('wallet', $payment->gateway);
        $this->assertSame(10000.0, $transaction->amount);
        $this->assertSame(10000.0, $payment->meta['charged_amount']);
        $this->assertSame('SAVE5000', $payment->meta['discount_redemption']['discount_code']);
        $this->assertSame(WalletTransaction::TYPE_AD_PURCHASE, $transaction->type);

        $balanceAfterFirstCharge = $wallet->balance;

        $secondPayment = $service($purchase);

        $wallet->refresh();

        $this->assertSame($payment->id, $secondPayment->id);
        $this->assertSame($balanceAfterFirstCharge, $wallet->balance);
    }
}
