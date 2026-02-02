<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'license_number' => 'LIC-' . $this->faker->unique()->numberBetween(1000, 9999),
            'experience_years' => $this->faker->numberBetween(1, 20),
            'certification' => $this->faker->word,
            'bio' => $this->faker->paragraph(),
            'education' => $this->faker->sentence(),
            'consultation_fee_online' => 100,
            'consultation_fee_inperson' => 150,
            'location' => $this->faker->city(),
            'is_verified' => true,
            'specialization_id' => Specialization::factory(),
        ];
    }
}
