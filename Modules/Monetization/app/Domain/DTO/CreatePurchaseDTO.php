<?php

namespace Modules\Monetization\Domain\DTO;

final class CreatePurchaseDTO
{
    public function __construct(
        public readonly int $adId,
        public readonly ?int $planId,
        public readonly ?string $planSlug,
        public readonly int $userId,
        public readonly ?string $gateway,
        public readonly ?string $correlationId,
        public readonly ?string $idempotencyKey,
        public readonly bool $payWithWallet = false,
    ) {
    }
}
