<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AdCategoryAssignment extends Pivot
{
    protected $table = 'ad_category_ad';

    protected $fillable = [
        'ad_id',
        'category_id',
        'is_primary',
        'assigned_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class, 'category_id');
    }
}
