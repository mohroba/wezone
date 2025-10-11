<?php

namespace Modules\Ad\Advertisable;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Modules\Ad\Advertisable\Contracts\AdvertisableTypeDefinition;

final class AdvertisableTypeRegistry
{
    /** @var array<string, AdvertisableTypeDefinition> */
    private array $definitionsByKey = [];

    /** @var array<string, AdvertisableTypeDefinition> */
    private array $definitionsByModel = [];

    /**
     * @param iterable<int, AdvertisableTypeDefinition> $definitions
     */
    public function __construct(iterable $definitions)
    {
        foreach ($definitions as $definition) {
            if (! $definition instanceof AdvertisableTypeDefinition) {
                throw new InvalidArgumentException('All advertisable type definitions must implement AdvertisableTypeDefinition.');
            }

            $this->definitionsByKey[$definition->key()] = $definition;
            $this->definitionsByModel[$definition->modelClass()] = $definition;
        }
    }

    /**
     * @return Collection<int, AdvertisableTypeDefinition>
     */
    public function all(): Collection
    {
        return collect($this->definitionsByKey)->values();
    }

    public function getByKey(string $key): ?AdvertisableTypeDefinition
    {
        return $this->definitionsByKey[$key] ?? null;
    }

    public function getByModel(string $modelClass): ?AdvertisableTypeDefinition
    {
        return $this->definitionsByModel[$modelClass] ?? null;
    }
}
