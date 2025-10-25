<?php

namespace Modules\Auth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profile extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const COLLECTION_NATIONAL_ID = 'national_id_document';
    public const COLLECTION_PROFILE_IMAGES = 'profile_images';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birth_date',
        'national_id',
        'residence_city_id',
        'residence_province_id',
        'last_seen_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'residence_city_id' => 'integer',
        'residence_province_id' => 'integer',
        'last_seen_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProfileFactory::new();
    }

    public function getFullNameAttribute(): ?string
    {
        $parts = array_filter([$this->first_name, $this->last_name]);

        return $parts !== [] ? implode(' ', $parts) : null;
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(self::COLLECTION_NATIONAL_ID)
            ->singleFile();

        $this->addMediaCollection(self::COLLECTION_PROFILE_IMAGES);
    }
}
