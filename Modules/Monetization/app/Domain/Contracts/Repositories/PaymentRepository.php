<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Modules\Monetization\Domain\Entities\Payment;

interface PaymentRepository
{
    public function create(array $attributes): Payment;

    public function update(Payment $payment, array $attributes): Payment;

    public function findById(int $id): ?Payment;

    public function findByIdempotencyKey(string $key, string $gateway): ?Payment;

    public function findByReference(string $refId): ?Payment;
}
