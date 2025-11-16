<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdAttributeDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_group_id',
        'key',
        'label',
        'help_text',
        'data_type',
        'unit',
        'options',
        'is_required',
        'is_filterable',
        'is_searchable',
        'validation_rules',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_searchable' => 'boolean',
    ];

    public function attributeGroup(): BelongsTo
    {
        return $this->belongsTo(AdAttributeGroup::class, 'attribute_group_id');
    }

    public function group(): BelongsTo
    {
        return $this->attributeGroup();
    }

    public function values(): HasMany
    {
        return $this->hasMany(AdAttributeValue::class, 'definition_id');
    }
}
