<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AdRealEstate extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'property_type',
        'usage_type',
        'area_m2',
        'land_area_m2',
        'bedrooms',
        'bathrooms',
        'parking_spaces',
        'floor_number',
        'total_floors',
        'year_built',
        'document_type',
        'has_elevator',
        'has_storage',
        'utilities_json',
    ];

    protected $casts = [
        'area_m2' => 'decimal:2',
        'land_area_m2' => 'decimal:2',
        'has_elevator' => 'boolean',
        'has_storage' => 'boolean',
        'utilities_json' => 'array',
    ];

    public function ad(): MorphOne
    {
        return $this->morphOne(Ad::class, 'advertisable');
    }
}
