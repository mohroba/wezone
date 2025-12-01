<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Ad\Models\AdvertisableType;
use Modules\Monetization\Database\Factories\PlanPriceOverrideFactory;

class PlanPriceOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'advertisable_type_id',
        'ad_category_id',
        'override_price',
        'currency',
        'discount_type',
        'discount_value',
        'discount_starts_at',
        'discount_ends_at',
        'usage_cap',
        'usage_count',
        'metadata',
    ];

    protected $casts = [
        'override_price' => 'float',
        'discount_value' => 'float',
        'discount_starts_at' => 'datetime',
        'discount_ends_at' => 'datetime',
        'usage_cap' => 'int',
        'usage_count' => 'int',
        'metadata' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function advertisableType(): BelongsTo
    {
        return $this->belongsTo(AdvertisableType::class);
    }

    protected static function newFactory(): PlanPriceOverrideFactory
    {
        return PlanPriceOverrideFactory::new();
    }
}
