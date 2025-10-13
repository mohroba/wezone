<?php

namespace Tests\Feature\Geography\Concerns;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;

trait CreatesGeography
{
    protected function seedGeography(): array
    {
        $iran = Country::factory()->create([
            'name' => 'ایران',
            'name_en' => 'Iran',
        ]);

        $iraq = Country::factory()->create([
            'name' => 'عراق',
            'name_en' => 'Iraq',
        ]);

        $tehranProvince = Province::factory()
            ->for($iran, 'countryRelation')
            ->create([
                'name' => 'تهران',
                'name_en' => 'Tehran',
                'latitude' => 35.6892,
                'longitude' => 51.3890,
            ]);

        $isfahanProvince = Province::factory()
            ->for($iran, 'countryRelation')
            ->create([
                'name' => 'اصفهان',
                'name_en' => 'Isfahan',
                'latitude' => 32.6525,
                'longitude' => 51.6746,
            ]);

        $baghdadProvince = Province::factory()
            ->for($iraq, 'countryRelation')
            ->create([
                'name' => 'بغداد',
                'name_en' => 'Baghdad',
                'latitude' => 33.3152,
                'longitude' => 44.3661,
            ]);

        $tehranCity = City::factory()
            ->for($tehranProvince, 'provinceRelation')
            ->create([
                'name' => 'تهران',
                'name_en' => 'Tehran',
                'latitude' => 35.6892,
                'longitude' => 51.3890,
            ]);

        $karajCity = City::factory()
            ->for($tehranProvince, 'provinceRelation')
            ->create([
                'name' => 'کرج',
                'name_en' => 'Karaj',
                'latitude' => 35.8327,
                'longitude' => 50.9915,
            ]);

        $isfahanCity = City::factory()
            ->for($isfahanProvince, 'provinceRelation')
            ->create([
                'name' => 'اصفهان',
                'name_en' => 'Isfahan',
                'latitude' => 32.6525,
                'longitude' => 51.6746,
            ]);

        $baghdadCity = City::factory()
            ->for($baghdadProvince, 'provinceRelation')
            ->create([
                'name' => 'بغداد',
                'name_en' => 'Baghdad',
                'latitude' => 33.3152,
                'longitude' => 44.3661,
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
