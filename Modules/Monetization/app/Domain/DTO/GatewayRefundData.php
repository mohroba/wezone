<?php

namespace Modules\Monetization\Domain\DTO;

use Modules\Monetization\Domain\Entities\Payment;

final class GatewayRefundData
{
    public function __construct(
        public readonly Payment $payment,
        public readonly ?float $amount = null,
        public readonly ?string $reason = null,
    ) {
    }
}
