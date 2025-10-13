<?php

namespace Modules\Monetization\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

final class Duration implements Stringable
{
    public function __construct(private readonly int $days)
    {
        if ($days <= 0) {
            throw new InvalidArgumentException('Duration must be positive.');
        }
    }

    public function days(): int
    {
        return $this->days;
    }

    public function toSeconds(): int
    {
        return $this->days * 86400;
    }

    public function __toString(): string
    {
        return (string) $this->days;
    }
}
