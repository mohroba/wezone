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
        public readonly int $advertisableTypeId,
        public readonly ?int $adCategoryId,
        public readonly ?string $discountCode,
        public readonly bool $payWithWallet = false,
    ) {
    }
}
