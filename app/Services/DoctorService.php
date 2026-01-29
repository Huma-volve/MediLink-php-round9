<?php

namespace App\Services;

use App\Models\Doctor;
use Illuminate\Support\Collection;

class DoctorService
{
    public function getTopRatedDoctors(int $limit = 5): Collection
    {
        $doctors = Doctor::with('reviews')->get();

        // if no doctors found return empty collection
        if ($doctors->isEmpty()) {
            return collect();
        }

        return $doctors
            ->filter(fn($doctor) => $doctor->reviews->count() > 0) // ignore doctors with no reviews
            ->sortByDesc(fn($doctor) => $doctor->reviews->avg('rating')) // sort by average rating
            ->take($limit) // take top 5 doctors
            ->values(); // reset keys
    }
}
