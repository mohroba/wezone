<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Monetization\Domain\Entities\DiscountRedemption;

interface DiscountRedemptionRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $attributes): DiscountRedemption;
}
