<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAW = 'withdraw';
    public const TYPE_REFUND = 'refund';
    public const TYPE_AD_PURCHASE = 'ad_purchase';
    public const TYPE_BONUS = 'bonus';

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'before_balance',
        'after_balance',
        'reference_type',
        'reference_id',
        'description',
        'meta',
    ];

    protected $casts = [
        'amount' => 'float',
        'before_balance' => 'float',
        'after_balance' => 'float',
        'meta' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
