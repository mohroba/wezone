<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Modules\Monetization\Database\Factories\PlanFactory as PlanModelFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property string $currency
 * @property int $duration_days
 * @property array|null $features
 * @property bool $active
 * @property int $order_column
 */
class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'duration_days',
        'features',
        'active',
        'order_column',
    ];

    protected $casts = [
        'price' => 'float',
        'features' => 'array',
        'active' => 'bool',
    ];

    protected function defaultFeatures(): Attribute
    {
        return Attribute::make(
            get: fn (?array $value) => $value ?? [],
        );
    }

    protected static function newFactory(): Factory
    {
        return PlanModelFactory::new();
    }
}
