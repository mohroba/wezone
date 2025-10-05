<?php

namespace Modules\Ad\Models;

use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsToMany(AdCategory::class, 'ad_category_ad')
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

    public function reports(): HasMany
    {
        return $this->hasMany(AdReport::class);
    }
}
