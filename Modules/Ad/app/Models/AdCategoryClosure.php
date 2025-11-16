<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdCategoryClosure extends Model
{
    public $timestamps = false;

    protected $table = 'ad_category_closure';

    protected $fillable = [
        'ancestor_id',
        'descendant_id',
        'depth',
        'advertisable_type_id',
    ];

    public function ancestor(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class, 'ancestor_id');
    }

    public function descendant(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class, 'descendant_id');
    }
}
