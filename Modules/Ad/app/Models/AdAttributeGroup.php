<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdAttributeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'advertisable_type',
        'category_id',
        'display_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class, 'category_id');
    }

    public function definitions(): HasMany
    {
        return $this->hasMany(AdAttributeDefinition::class, 'group_id');
    }

    protected static function newFactory(): Factory
    {
        return \Modules\Ad\Database\Factories\AdAttributeGroupFactory::new();
    }
}
