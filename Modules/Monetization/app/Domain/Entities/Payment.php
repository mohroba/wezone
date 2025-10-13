<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property float $amount
 * @property string $currency
 * @property string $gateway
 * @property string $status
 * @property string|null $ref_id
 * @property string|null $tracking_code
 */
class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'gateway',
        'status',
        'ref_id',
        'tracking_code',
        'request_payload',
        'response_payload',
        'paid_at',
        'failed_at',
        'refunded_at',
        'correlation_id',
        'idempotency_key',
    ];

    protected $casts = [
        'amount' => 'float',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected function requestPayload(): Attribute
    {
        return Attribute::make(
            get: fn (?array $value) => $value ?? [],
        );
    }

    protected function responsePayload(): Attribute
    {
        return Attribute::make(
            get: fn (?array $value) => $value ?? [],
        );
    }
}
