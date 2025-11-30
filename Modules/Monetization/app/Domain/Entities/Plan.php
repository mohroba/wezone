<?php

namespace Modules\Monetization\Domain\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property array|null $price_overrides
 * @property bool $active
 * @property int $order_column
 * @property int|null $bump_cooldown_minutes
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
        'price_overrides',
        'active',
        'order_column',
        'bump_cooldown_minutes',
    ];

    protected $casts = [
        'price' => 'float',
        'features' => 'array',
        'price_overrides' => 'array',
        'active' => 'bool',
        'bump_cooldown_minutes' => 'int',
    ];

    protected function defaultFeatures(): Attribute
    {
        return Attribute::make(
            get: fn (?array $value) => $value ?? [],
        );
    }

    protected function defaultPriceOverrides(): Attribute
    {
        return Attribute::make(
            get: fn (?array $value) => $value ?? [],
        );
    }

    public function priceOverrides(): HasMany
    {
        return $this->hasMany(PlanPriceOverride::class);
    }

    protected static function newFactory(): Factory
    {
        return PlanModelFactory::new();
    }
}
