<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\DTO\CreatePurchaseDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Events\PlanSelected;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class CreatePurchase
{
    public function __construct(
        private readonly PlanRepository $planRepository,
        private readonly PurchaseRepository $purchaseRepository,
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
            $purchase = $this->purchaseRepository->create([
                'ad_id' => $dto->adId,
                'plan_id' => $plan->getKey(),
                'user_id' => $dto->userId,
                'amount' => $plan->price,
                'currency' => $plan->currency,
                'payment_status' => 'draft',
                'payment_gateway' => $dto->gateway,
                'correlation_id' => $dto->correlationId,
                'idempotency_key' => $dto->idempotencyKey,
                'meta' => [
                    'pay_with_wallet' => $dto->payWithWallet,
                ],
                'bump_allowance' => (int) ($plan->features['bump']['allowance'] ?? 0),
            ]);

            Event::dispatch(new PlanSelected($purchase));

            return $purchase;
        });
    }
}
