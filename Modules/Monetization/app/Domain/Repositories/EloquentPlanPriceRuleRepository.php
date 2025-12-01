<?php

namespace Modules\Monetization\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Monetization\Domain\Contracts\Repositories\PlanPriceRuleRepository;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

class EloquentPlanPriceRuleRepository implements PlanPriceRuleRepository
{
    public function forPlan(Plan $plan, int $perPage = 15): LengthAwarePaginator
    {
        return $plan->priceOverrides()->with('discountCodes')->orderByDesc('id')->paginate($perPage);
    }

    public function findById(int $id): ?PlanPriceOverride
    {
        return PlanPriceOverride::query()->with('discountCodes')->find($id);
    }

    public function createForPlan(Plan $plan, array $attributes): PlanPriceOverride
    {
        return $plan->priceOverrides()->create($attributes);
    }

    public function update(PlanPriceOverride $priceRule, array $attributes): PlanPriceOverride
    {
        $priceRule->fill($attributes)->save();

        return $priceRule->loadMissing('discountCodes');
    }

    public function delete(PlanPriceOverride $priceRule): void
    {
        $priceRule->delete();
    }
}
