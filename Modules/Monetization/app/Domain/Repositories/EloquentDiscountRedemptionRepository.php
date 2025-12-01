<?php

namespace Modules\Monetization\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Monetization\Domain\Contracts\Repositories\DiscountRedemptionRepository;
use Modules\Monetization\Domain\Entities\DiscountRedemption;

class EloquentDiscountRedemptionRepository implements DiscountRedemptionRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = DiscountRedemption::query()->with(['discountCode', 'priceRule', 'purchase', 'user']);

        if (isset($filters['discount_code_id'])) {
            $query->where('discount_code_id', $filters['discount_code_id']);
        }

        if (isset($filters['price_rule_id'])) {
            $query->where('plan_price_override_id', $filters['price_rule_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['from'])) {
            $query->where('redeemed_at', '>=', $filters['from']);
        }

        if (isset($filters['to'])) {
            $query->where('redeemed_at', '<=', $filters['to']);
        }

        return $query->latest('redeemed_at')->paginate($perPage);
    }

    public function create(array $attributes): DiscountRedemption
    {
        return DiscountRedemption::query()->create($attributes);
    }
}
