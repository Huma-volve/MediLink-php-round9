<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorWorkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'doctor_id'    => $this->doctor_id,
            'day_of_week'  => $this->day_of_week,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
        ];
    }
}
