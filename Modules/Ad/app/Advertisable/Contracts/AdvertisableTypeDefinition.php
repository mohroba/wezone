<?php

namespace Modules\Ad\Advertisable\Contracts;

use Modules\Ad\Advertisable\DTO\AdvertisablePropertyDefinition;

interface AdvertisableTypeDefinition
{
    public function key(): string;

    public function label(): string;

    public function modelClass(): string;

    public function description(): ?string;

    /**
     * @return array<int, AdvertisablePropertyDefinition>
     */
    public function baseProperties(): array;
}
