<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'capital_city',
        'name',
        'name_en',
    ];

    protected $casts = [
        'capital_city' => 'int',
    ];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'country');
    }

    public function cities(): HasManyThrough
    {
        return $this->hasManyThrough(City::class, Province::class, 'country', 'province');
    }

    public function capitalCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'capital_city');
    }
}
