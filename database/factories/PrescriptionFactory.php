<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prescription>
 */
class PrescriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'prescription_number' => 'PRES-' . $this->faker->unique()->numberBetween(1000, 9999),
            'diagnosis' => $this->faker->sentence(),
            'medications' => ['Panadol', 'Antibiotic'],
            'frequency' => '3 times daily',
            'duration_days' => 7,
            'prescription_date' => now()->toDateString(),
        ];
    }
}
