<?php

namespace Modules\Monetization\Domain\DTO;

final class InitiatePaymentDTO
{
    public function __construct(
        public readonly int $purchaseId,
        public readonly int $userId,
        public readonly string $gateway,
        public readonly ?string $idempotencyKey,
        public readonly ?string $correlationId,
    ) {
    }
}
