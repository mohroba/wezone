<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdAttributeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'advertisable_type_id',
        'display_order',
    ];

    public function advertisableType(): BelongsTo
    {
        return $this->belongsTo(AdvertisableType::class, 'advertisable_type_id');
    }

    public function definitions(): HasMany
    {
        return $this->hasMany(AdAttributeDefinition::class, 'attribute_group_id');
    }
}
