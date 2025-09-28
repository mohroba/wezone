<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'province',
        'name',
        'name_en',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'province' => 'int',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function provinceRelation(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province');
    }
}
