<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

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
 * @property float|null $list_price
 * @property float|null $discounted_price
 * @property int|null $price_rule_id
 * @property string|null $discount_code
 * @property int|null $bump_cooldown_minutes
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 */
class AdPlanPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'plan_id',
        'user_id',
        'amount',
        'list_price',
        'discounted_price',
        'currency',
        'starts_at',
        'ends_at',
        'price_rule_id',
        'discount_code',
        'payment_status',
        'payment_gateway',
        'meta',
        'correlation_id',
        'idempotency_key',
        'bump_allowance',
        'bump_cooldown_minutes',
    ];

    protected $casts = [
        'meta' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'float',
        'list_price' => 'float',
        'discounted_price' => 'float',
        'bump_cooldown_minutes' => 'int',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function priceRule(): BelongsTo
    {
        return $this->belongsTo(PlanPriceOverride::class, 'price_rule_id');
    }

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function effectiveAmount(): float
    {
        return (float) ($this->discounted_price ?? $this->amount ?? $this->list_price ?? 0);
    }

    protected static function newFactory(): Factory
    {
        return \Modules\Monetization\Database\Factories\AdPlanPurchaseFactory::new();
    }
}
