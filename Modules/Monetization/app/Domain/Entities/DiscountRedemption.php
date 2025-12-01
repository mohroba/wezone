<?php

namespace Modules\Monetization\Domain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Monetization\Database\Factories\DiscountRedemptionFactory;

class DiscountRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_code_id',
        'plan_price_override_id',
        'ad_plan_purchase_id',
        'user_id',
        'amount_before',
        'amount_after',
        'discount_amount',
        'redeemed_at',
        'meta',
    ];

    protected $casts = [
        'amount_before' => 'float',
        'amount_after' => 'float',
        'discount_amount' => 'float',
        'redeemed_at' => 'datetime',
        'meta' => 'array',
    ];

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function priceRule(): BelongsTo
    {
        return $this->belongsTo(PlanPriceOverride::class, 'plan_price_override_id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(AdPlanPurchase::class, 'ad_plan_purchase_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): DiscountRedemptionFactory
    {
        return DiscountRedemptionFactory::new();
    }
}
