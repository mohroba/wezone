<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'definition_id',
        'advertisable_type',
        'advertisable_id',
        'value_string',
        'value_integer',
        'value_decimal',
        'value_boolean',
        'value_json',
        'normalized_value',
    ];

    protected $casts = [
        'value_decimal' => 'decimal:4',
        'value_boolean' => 'boolean',
        'value_json' => 'array',
    ];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(AdAttributeDefinition::class, 'definition_id');
    }

    public function advertisable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): Factory
    {
        return \Modules\Ad\Database\Factories\AdAttributeValueFactory::new();
    }
}
