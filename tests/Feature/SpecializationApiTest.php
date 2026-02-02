<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Specialization;

class SpecializationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_specializations_can_be_fetched()
    {
        Specialization::factory()->create([
            'name' => 'General Medicine',
            'description' => 'Diagnosis and treatment of adult diseases.'
        ]);

        Specialization::factory()->create([
            'name' => 'Pediatrics',
            'description' => 'Medical care for children.'
        ]);

        $response = $this->getJson('/api/specializations');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'description', 'created_at', 'updated_at']
                     ]
                 ]);

        $responseData = $response->json('data');
        $this->assertCount(2, $responseData);
        $this->assertEquals('General Medicine', $responseData[0]['name']);
        $this->assertEquals('Pediatrics', $responseData[1]['name']);
    }
}
