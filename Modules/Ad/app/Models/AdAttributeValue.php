<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'definition_id',
        'ad_id',
        'value_string',
        'value_integer',
        'value_decimal',
        'value_boolean',
        'value_date',
        'value_json',
        'normalized_value',
    ];

    protected $casts = [
        'value_decimal' => 'decimal:4',
        'value_boolean' => 'boolean',
        'value_date' => 'date',
        'value_json' => 'array',
    ];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(AdAttributeDefinition::class, 'definition_id');
    }

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class, 'ad_id');
    }
}
