<?php

namespace Database\Factories;

use App\Models\DoctorWorking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorWorking>
 */
class DoctorWorkingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
      protected $model = DoctorWorking::class;

    public function definition()
    {
        $openingHour = $this->faker->numberBetween(8, 11);
        $closingHour = $this->faker->numberBetween(16, 20);

        return [
            'day_of_week'  => $this->faker->numberBetween(0, 6),
            'opening_time' => sprintf('%02d:00:00', $openingHour),
            'closing_time' => sprintf('%02d:00:00', $closingHour),
            'is_closed'    => $this->faker->boolean(20),
        ];
    }
}
