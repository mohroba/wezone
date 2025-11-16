<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Ad\Database\Factories\AdCarFactory;

class AdCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'brand_id',
        'model_id',
        'year',
        'mileage',
        'fuel_type',
        'transmission',
        'body_style',
        'color',
        'condition',
        'ownership_count',
        'vin',
        'registration_expiry',
        'insurance_expiry',
    ];

    protected $casts = [
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
    ];

    public function ad(): MorphOne
    {
        return $this->morphOne(Ad::class, 'advertisable');
    }

    protected static function newFactory(): Factory
    {
        return AdCarFactory::new();
    }
}
