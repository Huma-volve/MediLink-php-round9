<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;



    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
     */

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'experience_years' => $this->faker->numberBetween(1, 20),
            'certification' => $this->faker->word,
            'education' => $this->faker->sentence(),
            'consultation_fee_online' => 100,
            'consultation_fee_inperson' => 150,
            'specialization_id' => Specialization::factory(),
            'license_number' => 'LIC-' . $this->faker->unique()->numberBetween(1000, 9999),
            'bio' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'is_verified' => true,
            'current_balance' => $this->faker->randomFloat(2, 0, 99999999.99),

        ];
    }
}
