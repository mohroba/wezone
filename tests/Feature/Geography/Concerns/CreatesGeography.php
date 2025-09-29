<?php

namespace Tests\Feature\Geography\Concerns;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;

trait CreatesGeography
{
    protected function seedGeography(): array
    {
        $iran = Country::create([
            'capital_city' => null,
            'name' => 'ایران',
            'name_en' => 'Iran',
        ]);

        $iraq = Country::create([
            'capital_city' => null,
            'name' => 'عراق',
            'name_en' => 'Iraq',
        ]);

        $tehranProvince = Province::create([
            'country' => $iran->id,
            'name' => 'تهران',
            'name_en' => 'Tehran',
            'latitude' => 35.68920000,
            'longitude' => 51.38900000,
        ]);

        $isfahanProvince = Province::create([
            'country' => $iran->id,
            'name' => 'اصفهان',
            'name_en' => 'Isfahan',
            'latitude' => 32.65250000,
            'longitude' => 51.67460000,
        ]);

        $baghdadProvince = Province::create([
            'country' => $iraq->id,
            'name' => 'بغداد',
            'name_en' => 'Baghdad',
            'latitude' => 33.31520000,
            'longitude' => 44.36610000,
        ]);

        $tehranCity = City::create([
            'province' => $tehranProvince->id,
            'name' => 'تهران',
            'name_en' => 'Tehran',
            'latitude' => 35.68920000,
            'longitude' => 51.38900000,
        ]);

        $karajCity = City::create([
            'province' => $tehranProvince->id,
            'name' => 'کرج',
            'name_en' => 'Karaj',
            'latitude' => 35.83270000,
            'longitude' => 50.99150000,
        ]);

        $isfahanCity = City::create([
            'province' => $isfahanProvince->id,
            'name' => 'اصفهان',
            'name_en' => 'Isfahan',
            'latitude' => 32.65250000,
            'longitude' => 51.67460000,
        ]);

        $baghdadCity = City::create([
            'province' => $baghdadProvince->id,
            'name' => 'بغداد',
            'name_en' => 'Baghdad',
            'latitude' => 33.31520000,
            'longitude' => 44.36610000,
        ]);

        $iran->update(['capital_city' => $tehranCity->id]);
        $iraq->update(['capital_city' => $baghdadCity->id]);

        return compact(
            'iran',
            'iraq',
            'tehranProvince',
            'isfahanProvince',
            'baghdadProvince',
            'tehranCity',
            'karajCity',
            'isfahanCity',
            'baghdadCity'
        );
    }
}
