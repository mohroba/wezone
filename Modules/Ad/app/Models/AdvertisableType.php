<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Ad\Database\Factories\AdvertisableTypeFactory;

class AdvertisableType extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'model_class',
        'description',
    ];

    public function attributeGroups(): HasMany
    {
        return $this->hasMany(AdAttributeGroup::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(AdCategory::class);
    }

    protected static function newFactory(): Factory
    {
        return AdvertisableTypeFactory::new();
    }
}
