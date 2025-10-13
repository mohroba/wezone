<?php

namespace Modules\Monetization\Domain\DTO;

use Modules\Monetization\Domain\Entities\Payment;

final class GatewayVerificationData
{
    public function __construct(
        public readonly Payment $payment,
        public readonly array $payload,
    ) {
    }
}
