<?php

namespace Modules\Monetization\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Monetization\Domain\Contracts\Repositories\DiscountCodeRepository;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

class EloquentDiscountCodeRepository implements DiscountCodeRepository
{
    public function forPriceRule(PlanPriceOverride $priceRule, int $perPage = 15): LengthAwarePaginator
    {
        return $priceRule->discountCodes()->latest()->paginate($perPage);
    }

    public function listActiveForRule(PlanPriceOverride $priceRule): Collection
    {
        return $priceRule->discountCodes()
            ->where(function ($query): void {
                $now = now();
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query): void {
                $now = now();
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->get();
    }

    public function findById(int $id): ?DiscountCode
    {
        return DiscountCode::query()->with('priceRule')->find($id);
    }

    public function findActiveByCode(string $code): ?DiscountCode
    {
        $normalized = Str::lower(trim($code));

        return DiscountCode::query()
            ->whereRaw('LOWER(code) = ?', [$normalized])
            ->where(function ($query): void {
                $now = now();
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query): void {
                $now = now();
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->first();
    }

    public function createForRule(PlanPriceOverride $priceRule, array $attributes): DiscountCode
    {
        return $priceRule->discountCodes()->create(array_merge($attributes, [
            'plan_id' => $priceRule->plan_id,
        ]));
    }

    public function update(DiscountCode $discountCode, array $attributes): DiscountCode
    {
        $discountCode->fill($attributes)->save();

        return $discountCode;
    }

    public function delete(DiscountCode $discountCode): void
    {
        $discountCode->delete();
    }
}
