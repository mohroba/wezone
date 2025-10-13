<?php

namespace Modules\Monetization\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

final class Currency implements Stringable
{
    public function __construct(private readonly string $code)
    {
        if (strlen($code) !== 3) {
            throw new InvalidArgumentException('Currency code must be ISO 4217.');
        }
    }

    public function code(): string
    {
        return strtoupper($this->code);
    }

    public function equals(self $other): bool
    {
        return $this->code() === $other->code();
    }

    public function __toString(): string
    {
        return $this->code();
    }
}
