<?php

namespace Modules\Ad\Advertisable\Definitions;

use Modules\Ad\Advertisable\Contracts\AdvertisableTypeDefinition;
use Modules\Ad\Advertisable\DTO\AdvertisablePropertyDefinition;

abstract class AbstractAdvertisableTypeDefinition implements AdvertisableTypeDefinition
{
    public function description(): ?string
    {
        return null;
    }

    /**
     * @return array<int, AdvertisablePropertyDefinition>
     */
    final public function baseProperties(): array
    {
        return $this->defineBaseProperties();
    }

    /**
     * @return array<int, AdvertisablePropertyDefinition>
     */
    abstract protected function defineBaseProperties(): array;
}
