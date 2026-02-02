<?php

namespace Database\Factories;


use App\Models\Doctor;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Specialization;


class DoctorFactory extends Factory
{
    protected $model = Doctor::class;




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
            'user_id' => User::factory(),

            'license_number' => $this->faker->unique()->numerify('LIC-#####'),
            'experience_years' => $this->faker->numberBetween(1, 20),
            'certification' => $this->faker->word,
            'bio' => $this->faker->paragraph,
            'education' => $this->faker->sentence,
            'consultation_fee_online' => 100,
            'consultation_fee_inperson' => 150,
        
            'location' => $this->faker->city,
            'is_verified' => true,

            'specialization_id' => Specialization::factory(),
            'license_number' => 'LIC-' . $this->faker->unique()->numberBetween(1000, 9999),
            'bio' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'is_verified' => true,
            'experience_years' => $this->faker->numberBetween(1, 20),

        ];
    }
}
