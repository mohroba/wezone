<?php

namespace Modules\Monetization\Domain\ValueObjects;

final class FeatureSet
{
    public function __construct(private readonly array $features)
    {
    }

    public static function fromArray(array $features): self
    {
        return new self($features);
    }

    public function all(): array
    {
        return $this->features;
    }

    public function has(string $feature): bool
    {
        return array_key_exists($feature, $this->features);
    }

    public function get(string $feature, mixed $default = null): mixed
    {
        return $this->features[$feature] ?? $default;
    }
}
