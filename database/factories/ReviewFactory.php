<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'patient_id' => Patient::factory(),   // لازم عشان FK
            'appointment_id' => null,             // nullable عندك
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->optional()->paragraph(),
        ];
    }
}
