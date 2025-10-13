<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'depth',
        'path',
        'slug',
        'name',
        'name_localized',
        'is_active',
        'sort_order',
        'filters_schema',
    ];

    protected $casts = [
        'name_localized' => 'array',
        'filters_schema' => 'array',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function ads(): BelongsToMany
    {
        return $this->belongsToMany(Ad::class, 'ad_category_ad')
            ->using(AdCategoryAssignment::class)
            ->withPivot(['is_primary', 'assigned_by'])
            ->withTimestamps();
    }

    public function attributeGroups(): HasMany
    {
        return $this->hasMany(AdAttributeGroup::class, 'category_id');
    }

    public function ancestors(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'ad_category_closure',
            'descendant_id',
            'ancestor_id'
        )->withPivot('depth');
    }

    public function descendants(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'ad_category_closure',
            'ancestor_id',
            'descendant_id'
        )->withPivot('depth');
    }

    protected static function newFactory(): Factory
    {
        return \Modules\Ad\Database\Factories\AdCategoryFactory::new();
    }
}
