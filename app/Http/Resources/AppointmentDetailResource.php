<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'appointment_date' => $this->appointment_date,
                'appointment_time' => $this->appointment_time,
                'consultation_type' => $this->consultation_type,
                'consultation_fee' => $this->consultation_type === 'online'
                    ? $this->doctor->consultation_fee_online
                    : $this->doctor->consultation_fee_inperson,
            ];
    }
}
