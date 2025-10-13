<?php

namespace Modules\Monetization\Domain\DTO;

final class VerifyPaymentDTO
{
    public function __construct(
        public readonly string $gateway,
        public readonly array $payload,
        public readonly ?string $idempotencyKey,
        public readonly ?string $correlationId,
    ) {
    }
}
