<?php

namespace Modules\Monetization\Domain\Repositories;

use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\Entities\Payment;

class EloquentPaymentRepository implements PaymentRepository
{
    public function create(array $attributes): Payment
    {
        return Payment::query()->create($attributes);
    }

    public function update(Payment $payment, array $attributes): Payment
    {
        $payment->fill($attributes)->save();

        return $payment;
    }

    public function findById(int $id): ?Payment
    {
        return Payment::query()->find($id);
    }

    public function findByIdempotencyKey(string $key, string $gateway): ?Payment
    {
        return Payment::query()
            ->where('idempotency_key', $key)
            ->where('gateway', $gateway)
            ->first();
    }

    public function findByReference(string $refId): ?Payment
    {
        return Payment::query()->where('ref_id', $refId)->first();
    }
}
