<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PrivacySettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'is_visible' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
            'data_sharing' => $this->faker->boolean,
            'two_factor_auth' => $this->faker->boolean(50),
        ];
    }
}
