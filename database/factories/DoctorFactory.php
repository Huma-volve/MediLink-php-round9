<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Spelization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'license_number' => $this->faker->unique()->bothify('LIC-#####-###'),
            'experience_years' => $this->faker->optional()->numberBetween(0, 40),
            'certification' => $this->faker->optional()->sentence(3),
            'bio' => $this->faker->optional()->paragraph(),
            'education' => $this->faker->optional()->sentence(6),
            'consultation_fee_online' => $this->faker->optional()->randomFloat(2, 10, 500),
            'consultation_fee_inperson' => $this->faker->optional()->randomFloat(2, 10, 500),

            // FK required
            'spelization_id' => Spelization::factory(),

            'location' => $this->faker->optional()->city(),
            'is_verified' => $this->faker->boolean(),
        ];
    }
}
