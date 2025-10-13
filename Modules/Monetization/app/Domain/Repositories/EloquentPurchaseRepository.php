<?php

namespace Modules\Monetization\Domain\Repositories;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPurchaseRepository implements PurchaseRepository
{
    public function create(array $attributes): AdPlanPurchase
    {
        return AdPlanPurchase::query()->create($attributes);
    }

    public function update(AdPlanPurchase $purchase, array $attributes): AdPlanPurchase
    {
        $purchase->fill($attributes)->save();

        return $purchase;
    }

    public function findById(int $id): ?AdPlanPurchase
    {
        return AdPlanPurchase::query()->find($id);
    }

    public function findByCorrelationId(string $correlationId): ?AdPlanPurchase
    {
        return AdPlanPurchase::query()->where('correlation_id', $correlationId)->first();
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return AdPlanPurchase::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
