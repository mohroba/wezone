<?php

namespace Modules\Ad\Models;

use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ad extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    public const COLLECTION_IMAGES = 'ad_images';
    public const CONVERSION_THUMB = 'thumb';
    public const CONVERSION_MEDIUM = 'medium';

    protected $fillable = [
        'user_id',
        'advertisable_type_id',
        'advertisable_type',
        'advertisable_id',
        'slug',
        'title',
        'subtitle',
        'description',
        'status',
        'published_at',
        'expires_at',
        'price_amount',
        'price_currency',
        'is_negotiable',
        'is_exchangeable',
        'city_id',
        'province_id',
        'latitude',
        'longitude',
        'contact_channel',
        'view_count',
        'share_count',
        'favorite_count',
        'like_count',
        'featured_until',
        'priority_score',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'featured_until' => 'datetime',
        'contact_channel' => 'array',
        'is_negotiable' => 'boolean',
        'is_exchangeable' => 'boolean',
    ];

    // 🔹 Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function advertisableType(): BelongsTo
    {
        return $this->belongsTo(AdvertisableType::class, 'advertisable_type_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function advertisable(): MorphTo
    {
        return $this->morphTo();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(AdCategory::class, 'ad_category_ad', 'ad_id', 'category_id')
            ->using(AdCategoryAssignment::class)
            ->withPivot(['is_primary', 'assigned_by'])
            ->withTimestamps();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(AdStatusHistory::class);
    }

    public function slugHistories(): HasMany
    {
        return $this->hasMany(AdSlugHistory::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(AdFavorite::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(AdLike::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(AdReport::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(AdAttributeValue::class, 'ad_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AdComment::class);
    }

    public function planPurchases(): HasMany
    {
        return $this->hasMany(AdPlanPurchase::class, 'ad_id');
    }

    public function payments(): HasManyThrough
    {
        return $this
            ->hasManyThrough(
                Payment::class,
                AdPlanPurchase::class,
                'ad_id',
                'payable_id',
                'id',
                'id'
            )
            ->where('payments.payable_type', AdPlanPurchase::class);
    }

    // 🔹 Factory
    protected static function newFactory(): Factory
    {
        return \Modules\Ad\Database\Factories\AdFactory::new();
    }

    // 🔹 Spatie Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COLLECTION_IMAGES);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion(self::CONVERSION_THUMB)
            ->fit(Fit::Crop, 320, 320)
            ->nonQueued()
            ->performOnCollections(self::COLLECTION_IMAGES);

        $this
            ->addMediaConversion(self::CONVERSION_MEDIUM)
            ->fit(Fit::Max, 1280, 960)
            ->optimize()
            ->nonQueued()
            ->performOnCollections(self::COLLECTION_IMAGES);
    }
}
