<?php

namespace Modules\Monetization\Domain\ValueObjects;

use InvalidArgumentException;

final class Money
{
    public function __construct(
        private readonly int|float $amount,
        private readonly Currency $currency,
    ) {}

    public static function fromFloat(float $amount, string $currency): self
    {
        return new self($amount, new Currency($currency));
    }

    public function amount(): float
    {
        return (float) $this->amount;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function assertSameCurrency(self $other): void
    {
        if (! $this->currency->equals($other->currency())) {
            throw new InvalidArgumentException('Currencies do not match.');
        }
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self($this->amount() + $other->amount(), $this->currency);
    }

    public function subtract(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self($this->amount() - $other->amount(), $this->currency);
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount(),
            'currency' => $this->currency()->code(),
        ];
    }
}
