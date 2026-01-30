<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),

            'spelization_id' => \App\Models\Spelization::factory(),

            'license_number' => 'LIC-' . $this->faker->unique()->numberBetween(1000, 9999),
            'bio' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'is_verified' => true,
            'experience_years' => $this->faker->numberBetween(1, 20),
        ];
    }
}
