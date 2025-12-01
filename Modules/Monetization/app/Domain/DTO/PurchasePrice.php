<?php

namespace Modules\Monetization\Domain\DTO;

use Modules\Monetization\Domain\Entities\PlanPriceOverride;

final class PurchasePrice
{
    public function __construct(
        public readonly float $listPrice,
        public readonly float $discountedPrice,
        public readonly string $currency,
        public readonly ?PlanPriceOverride $priceRule,
        public readonly ?string $discountCode,
        public readonly bool $discountApplied,
    ) {
    }
}
