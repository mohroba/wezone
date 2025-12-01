<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\DTO\CreatePurchaseDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Events\PlanSelected;
use Modules\Monetization\Domain\Services\PurchasePricingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class CreatePurchase
{
    public function __construct(
        private readonly PlanRepository $planRepository,
        private readonly PurchaseRepository $purchaseRepository,
        private readonly PurchasePricingService $pricingService,
    ) {
    }

    public function __invoke(CreatePurchaseDTO $dto): AdPlanPurchase
    {
        $plan = $dto->planSlug
            ? $this->planRepository->findBySlug($dto->planSlug)
            : ($dto->planId ? $this->planRepository->findById($dto->planId) : null);
        if (! $plan) {
            throw new InvalidArgumentException('Selected plan not found.');
        }

        return DB::transaction(function () use ($dto, $plan): AdPlanPurchase {
            $pricing = $this->pricingService->calculate(
                plan: $plan,
                advertisableTypeId: $dto->advertisableTypeId,
                adCategoryId: $dto->adCategoryId,
                discountCode: $dto->discountCode,
                userId: $dto->userId,
            );

            $purchase = $this->purchaseRepository->create([
                'ad_id' => $dto->adId,
                'plan_id' => $plan->getKey(),
                'price_rule_id' => $pricing->priceRule?->getKey(),
                'user_id' => $dto->userId,
                'amount' => $pricing->discountedPrice,
                'list_price' => $pricing->listPrice,
                'discounted_price' => $pricing->discountedPrice,
                'currency' => $pricing->currency,
                'payment_status' => 'draft',
                'payment_gateway' => $dto->gateway,
                'correlation_id' => $dto->correlationId,
                'idempotency_key' => $dto->idempotencyKey,
                'discount_code' => $pricing->discountCode,
                'meta' => [
                    'pay_with_wallet' => $dto->payWithWallet,
                    'pricing' => [
                        'list_price' => $pricing->listPrice,
                        'discounted_price' => $pricing->discountedPrice,
                        'currency' => $pricing->currency,
                        'price_rule_id' => $pricing->priceRule?->getKey(),
                        'discount_code' => $pricing->discountCode,
                        'discount_applied' => $pricing->discountApplied,
                    ],
                ],
                'bump_allowance' => (int) ($plan->features['bump']['allowance'] ?? 0),
                'bump_cooldown_minutes' => $plan->bump_cooldown_minutes
                    ?? $plan->features['bump']['cooldown_minutes']
                    ?? null,
            ]);

            if ($pricing->priceRule && $pricing->discountApplied && $pricing->priceRule->usage_cap !== null) {
                $pricing->priceRule->increment('usage_count');
            }

            Event::dispatch(new PlanSelected($purchase));

            return $purchase;
        });
    }
}
