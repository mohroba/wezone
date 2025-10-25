<?php

namespace Modules\Ad\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdComment;

/**
 * @extends Factory<AdComment>
 */
class AdCommentFactory extends Factory
{
    protected $model = AdComment::class;

    public function definition(): array
    {
        return [
            'ad_id' => Ad::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'body' => $this->faker->sentences(2, true),
        ];
    }
}
