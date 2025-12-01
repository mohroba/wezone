<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

interface DiscountCodeRepository
{
    public function forPriceRule(PlanPriceOverride $priceRule, int $perPage = 15): LengthAwarePaginator;

    public function listActiveForRule(PlanPriceOverride $priceRule): Collection;

    public function findById(int $id): ?DiscountCode;

    public function findActiveByCode(string $code): ?DiscountCode;

    public function createForRule(PlanPriceOverride $priceRule, array $attributes): DiscountCode;

    public function update(DiscountCode $discountCode, array $attributes): DiscountCode;

    public function delete(DiscountCode $discountCode): void;
}
