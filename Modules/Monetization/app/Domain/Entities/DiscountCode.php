<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Monetization\Database\Factories\DiscountCodeFactory;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'plan_price_override_id',
        'code',
        'description',
        'starts_at',
        'ends_at',
        'usage_cap',
        'usage_count',
        'per_user_cap',
        'is_stackable',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'usage_cap' => 'int',
        'usage_count' => 'int',
        'per_user_cap' => 'int',
        'is_stackable' => 'bool',
        'metadata' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function priceRule(): BelongsTo
    {
        return $this->belongsTo(PlanPriceOverride::class, 'plan_price_override_id');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(DiscountRedemption::class);
    }

    protected static function newFactory(): DiscountCodeFactory
    {
        return DiscountCodeFactory::new();
    }
}
