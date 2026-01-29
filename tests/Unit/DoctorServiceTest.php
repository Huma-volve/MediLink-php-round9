<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DoctorService;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_top_rated_doctors()
    {
        $doctor1 = Doctor::factory()->create();
        $doctor2 = Doctor::factory()->create();

        Review::factory()->create([
            'doctor_id' => $doctor1->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'doctor_id' => $doctor2->id,
            'rating' => 3,
        ]);

        $service = new DoctorService();
        $result = $service->getTopRatedDoctors();

        $this->assertCount(2, $result);
        $this->assertEquals($doctor1->id, $result->first()->id);
    }

    public function test_it_returns_empty_collection_if_no_reviews()
    {
        Doctor::factory()->count(3)->create();

        $service = new DoctorService();
        $result = $service->getTopRatedDoctors();

        $this->assertTrue($result->isEmpty());
        $this->assertCount(0, $result);
    }
}
