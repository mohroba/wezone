<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

interface PlanPriceRuleRepository
{
    public function forPlan(Plan $plan, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?PlanPriceOverride;

    public function createForPlan(Plan $plan, array $attributes): PlanPriceOverride;

    public function update(PlanPriceOverride $priceRule, array $attributes): PlanPriceOverride;

    public function delete(PlanPriceOverride $priceRule): void;
}
