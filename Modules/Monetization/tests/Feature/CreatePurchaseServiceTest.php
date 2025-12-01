<?php

namespace Modules\Monetization\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Ad\Database\Factories\AdFactory;
use Modules\Ad\Database\Factories\AdvertisableTypeFactory;
use Modules\Ad\Models\AdCategory;
use Modules\Monetization\Domain\DTO\CreatePurchaseDTO;
use Modules\Monetization\Domain\Repositories\EloquentPlanRepository;
use Modules\Monetization\Domain\Repositories\EloquentPurchaseRepository;
use Modules\Monetization\Domain\Services\CreatePurchase;
use Modules\Monetization\Domain\Services\PurchasePricingService;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Repositories\EloquentDiscountRedemptionRepository;
use Modules\Monetization\Domain\Services\DiscountRedemptionService;
use Tests\TestCase;

class CreatePurchaseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_persists_pricing_details_and_cooldown(): void
    {
        $plan = Plan::factory()->create([
            'price' => 6000,
            'currency' => 'IRR',
            'bump_cooldown_minutes' => 30,
        ]);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $category = AdCategory::factory()->for($advertisableType, 'advertisableType')->create();

        $override = PlanPriceOverride::factory()
            ->for($plan, 'plan')
            ->for($advertisableType, 'advertisableType')
            ->state([
                'ad_category_id' => $category->id,
                'override_price' => 5000,
                'currency' => 'IRR',
                'discount_type' => 'fixed',
                'discount_value' => 500,
                'usage_cap' => 3,
                'metadata' => ['discount_codes' => ['PROMO500']],
            ])
            ->create();

        $discountCode = DiscountCode::factory()
            ->for($plan, 'plan')
            ->for($override, 'priceRule')
            ->state([
                'code' => 'PROMO500',
                'usage_cap' => 5,
                'per_user_cap' => 2,
            ])
            ->create();

        $user = User::factory()->create();
        $ad = AdFactory::new()
            ->for($user)
            ->state([
                'advertisable_type_id' => $advertisableType->id,
            ])
            ->create();

        $service = new CreatePurchase(
            planRepository: new EloquentPlanRepository(),
            purchaseRepository: new EloquentPurchaseRepository(),
            pricingService: new PurchasePricingService(),
            discountRedemptionService: new DiscountRedemptionService(new EloquentDiscountRedemptionRepository()),
        );

        $purchase = $service(new CreatePurchaseDTO(
            adId: $ad->id,
            planId: $plan->id,
            planSlug: null,
            userId: $user->id,
            gateway: 'payping',
            correlationId: Str::uuid()->toString(),
            idempotencyKey: Str::uuid()->toString(),
            advertisableTypeId: $advertisableType->id,
            adCategoryId: $category->id,
            discountCode: 'PROMO500',
            payWithWallet: true,
        ));

        $this->assertSame(5000.0, $purchase->list_price);
        $this->assertSame(4500.0, $purchase->discounted_price);
        $this->assertSame(4500.0, $purchase->amount);
        $this->assertSame($override->id, $purchase->price_rule_id);
        $this->assertSame($discountCode->id, $purchase->discount_code_id);
        $this->assertSame('PROMO500', $purchase->discount_code);
        $this->assertSame(30, $purchase->bump_cooldown_minutes);
        $this->assertTrue($purchase->meta['pay_with_wallet']);
        $this->assertSame(4500, $purchase->meta['pricing']['discounted_price']);
        $this->assertSame(1, $override->fresh()->usage_count);
        $this->assertSame(1, $discountCode->fresh()->usage_count);
    }

    public function test_purchase_uses_plan_feature_cooldown_when_no_direct_value(): void
    {
        $plan = Plan::factory()->create([
            'price' => 2500,
            'currency' => 'IRR',
            'bump_cooldown_minutes' => null,
            'features' => [
                'bump' => [
                    'allowance' => 2,
                    'cooldown_minutes' => 75,
                ],
            ],
        ]);

        $advertisableType = AdvertisableTypeFactory::new()->create();
        $category = AdCategory::factory()->for($advertisableType, 'advertisableType')->create();
        $user = User::factory()->create();
        $ad = AdFactory::new()->for($user)->state([
            'advertisable_type_id' => $advertisableType->id,
        ])->create();

        $service = new CreatePurchase(
            planRepository: new EloquentPlanRepository(),
            purchaseRepository: new EloquentPurchaseRepository(),
            pricingService: new PurchasePricingService(),
            discountRedemptionService: new DiscountRedemptionService(new EloquentDiscountRedemptionRepository()),
        );

        $purchase = $service(new CreatePurchaseDTO(
            adId: $ad->id,
            planId: $plan->id,
            planSlug: null,
            userId: $user->id,
            gateway: 'payping',
            correlationId: Str::uuid()->toString(),
            idempotencyKey: Str::uuid()->toString(),
            advertisableTypeId: $advertisableType->id,
            adCategoryId: $category->id,
            discountCode: null,
            payWithWallet: false,
        ));

        $this->assertSame(75, $purchase->bump_cooldown_minutes);
        $this->assertSame(2, $purchase->bump_allowance);
    }
}
