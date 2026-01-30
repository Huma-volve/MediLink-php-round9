<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'status' => $this->status,
            'reason_for_visit' => $this->reason_for_visit,
            'consultation_type' => $this->consultation_type,
            'patient' => new PatientResource($this->patient),
        ];
    }
}
