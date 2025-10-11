<?php

namespace Modules\Ad\Advertisable\DTO;

use Illuminate\Support\Collection;
use Modules\Ad\Advertisable\Contracts\AdvertisableTypeDefinition;

final class AdvertisableTypeMetadata
{
    public function __construct(
        public readonly AdvertisableTypeDefinition $definition,
        public readonly Collection $attributeGroups,
    ) {
    }
}
