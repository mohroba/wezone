<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'country',
        'name',
        'name_en',
    ];

    protected $casts = [
        'country' => 'int',
    ];

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province');
    }
}
