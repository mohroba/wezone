<?php

namespace Modules\Monetization\Database\Seeders;

use Modules\Monetization\Domain\Entities\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'فوری',
                'slug' => 'fori',
                'description' => 'نمایش فوری آگهی با نشان ویژه.',
                'price' => 150000,
                'currency' => 'IRR',
                'duration_days' => 7,
                'features' => [
                    'urgent_badge' => [
                        'urgent_duration' => 7,
                    ],
                ],
                'order_column' => 1,
            ],
            [
                'name' => 'متمایز',
                'slug' => 'motemayez',
                'description' => 'نمایش متمایز در نتایج جستجو.',
                'price' => 200000,
                'currency' => 'IRR',
                'duration_days' => 10,
                'features' => [
                    'highlight' => true,
                ],
                'order_column' => 2,
            ],
            [
                'name' => 'نردبان',
                'slug' => 'nardeban',
                'description' => 'بالا آوردن آگهی در لیست با امکان نردبان.',
                'price' => 250000,
                'currency' => 'IRR',
                'duration_days' => 7,
                'features' => [
                    'bump' => [
                        'urgent_duration' => 0,
                        'allowance' => 5,
                    ],
                ],
                'order_column' => 3,
            ],
            [
                'name' => 'سوپر',
                'slug' => 'super',
                'description' => 'ترکیبی از همه امکانات ویژه.',
                'price' => 400000,
                'currency' => 'IRR',
                'duration_days' => 15,
                'features' => [
                    'urgent_badge' => [
                        'urgent_duration' => 15,
                    ],
                    'highlight' => true,
                    'bump' => [
                        'urgent_duration' => 0,
                        'allowance' => 10,
                    ],
                ],
                'order_column' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['slug' => $plan['slug']],
                [
                    'name' => $plan['name'],
                    'description' => $plan['description'],
                    'price' => $plan['price'],
                    'currency' => $plan['currency'],
                    'duration_days' => $plan['duration_days'],
                    'features' => $plan['features'],
                    'active' => true,
                    'order_column' => $plan['order_column'],
                ]
            );
        }
    }
}
