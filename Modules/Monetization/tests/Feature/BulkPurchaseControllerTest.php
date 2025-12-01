<?php

namespace Modules\Monetization\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Repositories\EloquentWalletRepository;
use Tests\TestCase;

class BulkPurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(User::factory()->create());
    }

    public function test_can_create_multiple_purchases_and_pay_with_wallet(): void
    {
        $user = auth()->user();
        $ad = Ad::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        $primaryPlan = Plan::factory()->create([
            'price' => 5000,
            'currency' => 'IRR',
            'features' => ['bump' => ['allowance' => 1]],
        ]);

        $secondaryPlan = Plan::factory()->create([
            'price' => 9000,
            'currency' => 'IRR',
            'features' => ['highlight' => true],
        ]);

        $walletRepository = new EloquentWalletRepository();
        $wallet = $walletRepository->findOrCreateForUser($user->id, 'IRR');
        $walletRepository->updateBalance($wallet, 20000);

        $response = $this->postJson('/api/monetization/purchases/bulk', [
            'ad_id' => $ad->id,
            'advertisable_type_id' => $ad->advertisable_type_id,
            'plans' => [
                [
                    'plan_id' => $primaryPlan->id,
                    'pay_with_wallet' => true,
                    'gateway' => 'wallet',
                ],
                [
                    'plan_slug' => $secondaryPlan->slug,
                    'gateway' => 'payping',
                    'discount_code' => 'BULK10',
                ],
            ],
        ], [
            'X-Correlation-Id' => 'bulk-correlation',
            'X-Idempotency-Key' => 'bulk-idempotency',
        ]);

        $response->assertCreated();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.ad_id', $ad->id);
        $response->assertJsonPath('data.1.plan.id', $secondaryPlan->id);
        $response->assertJsonPath('payments.0.gateway', 'wallet');

        $this->assertDatabaseCount('ad_plan_purchases', 2);

        $purchaseIds = collect($response->json('data'))->pluck('id');

        $this->assertDatabaseHas('ad_plan_purchases', [
            'id' => $purchaseIds[0],
            'payment_status' => 'active',
            'correlation_id' => 'bulk-correlation',
        ]);

        $this->assertDatabaseHas('ad_plan_purchases', [
            'id' => $purchaseIds[1],
            'payment_status' => 'draft',
            'correlation_id' => 'bulk-correlation',
        ]);

        $this->assertDatabaseHas('payments', [
            'payable_type' => 'Modules\\Monetization\\Domain\\Entities\\AdPlanPurchase',
            'payable_id' => $purchaseIds[0],
            'gateway' => 'wallet',
            'status' => 'paid',
        ]);
    }
}
