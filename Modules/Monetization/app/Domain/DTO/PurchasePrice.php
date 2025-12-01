<?php

namespace Modules\Monetization\Domain\DTO;

use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Entities\DiscountCode;

final class PurchasePrice
{
    public function __construct(
        public readonly float $listPrice,
        public readonly float $discountedPrice,
        public readonly string $currency,
        public readonly ?PlanPriceOverride $priceRule,
        public readonly ?string $discountCode,
        public readonly ?DiscountCode $discountCodeEntity,
        public readonly bool $discountApplied,
    ) {
    }
}
