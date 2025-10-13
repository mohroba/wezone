<?php

namespace Modules\Monetization\Domain\ValueObjects;

use Illuminate\Support\Str;

final class IdempotencyKey
{
    public function __construct(private readonly string $value)
    {
    }

    public static function generate(): self
    {
        return new self(Str::uuid()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
