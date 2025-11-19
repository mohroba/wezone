<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Ad\Models\AdvertisableType;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AdCategory extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const COLLECTION_ICON = 'icon';

    protected $fillable = [
        'advertisable_type_id',
        'parent_id',
        'slug',
        'name',
        'name_localized',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'name_localized' => 'array',
        'is_active' => 'boolean',
    ];

    public function advertisableType(): BelongsTo
    {
        return $this->belongsTo(AdvertisableType::class);
    }

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COLLECTION_ICON)->singleFile();
    }
}
