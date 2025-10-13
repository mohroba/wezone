<?php

namespace Modules\Monetization\Domain\DTO;

use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\ValueObjects\Money;

final class GatewayInitiationData
{
    public function __construct(
        public readonly AdPlanPurchase $purchase,
        public readonly Plan $plan,
        public readonly Money $money,
        public readonly ?string $callbackUrl = null,
    ) {
    }
}
