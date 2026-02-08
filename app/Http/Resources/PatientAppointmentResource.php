<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientAppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'doctor_name' => $this->doctor->user->name,
            'specialization_name' => $this->doctor->specialization->name,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'consultation_type' => $this->consultation_type,
        ];
    }
}
