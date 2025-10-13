<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PurchaseRepository
{
    public function create(array $attributes): AdPlanPurchase;

    public function update(AdPlanPurchase $purchase, array $attributes): AdPlanPurchase;

    public function findById(int $id): ?AdPlanPurchase;

    public function findByCorrelationId(string $correlationId): ?AdPlanPurchase;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
}
