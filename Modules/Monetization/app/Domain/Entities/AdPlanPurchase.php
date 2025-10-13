<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $ad_id
 * @property int $plan_id
 * @property int $user_id
 * @property float $amount
 * @property string $currency
 * @property string $payment_status
 * @property string|null $payment_gateway
 * @property array|null $meta
 * @property string|null $correlation_id
 * @property string|null $idempotency_key
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 */
class AdPlanPurchase extends Model
{
    protected $fillable = [
        'ad_id',
        'plan_id',
        'user_id',
        'amount',
        'currency',
        'starts_at',
        'ends_at',
        'payment_status',
        'payment_gateway',
        'meta',
        'correlation_id',
        'idempotency_key',
        'bump_allowance',
    ];

    protected $casts = [
        'meta' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'float',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payable_id')->where('payable_type', self::class);
    }

    protected function effectiveMeta(): Attribute
    {
        return Attribute::make(
            get: fn (?array $value) => $value ?? [],
        );
    }
}
