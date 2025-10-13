<?php

namespace Modules\Monetization\Domain\DTO;

final class RefundPaymentDTO
{
    public function __construct(
        public readonly int $paymentId,
        public readonly int $requestedBy,
        public readonly ?string $reason = null,
        public readonly ?string $correlationId = null,
    ) {
    }
}
