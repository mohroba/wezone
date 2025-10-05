<?php

namespace Modules\Ad\Support;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Models\AdRealEstate;

class AdvertisableType
{
    /**
     * Map of supported advertisable morph types to their underlying table names.
     */
    private const MAP = [
        AdCar::class => 'ad_cars',
        AdRealEstate::class => 'ad_real_estates',
        AdJob::class => 'ad_jobs',
    ];

    /**
     * Return the list of supported advertisable morph class names.
     *
     * @return array<int, class-string>
     */
    public static function allowed(): array
    {
        return array_keys(self::MAP);
    }

    /**
     * Determine if a given class string is a supported advertisable type.
     */
    public static function isAllowed(?string $class): bool
    {
        return $class !== null && array_key_exists($class, self::MAP);
    }

    /**
     * Resolve the database table name for the given advertisable class name.
     */
    public static function tableFor(string $class): string
    {
        if (! self::isAllowed($class)) {
            throw new InvalidArgumentException(sprintf('Unsupported advertisable type [%s].', $class));
        }

        return Arr::get(self::MAP, $class);
    }
}
