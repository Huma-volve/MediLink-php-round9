<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'license_number' => $this->license_number,
            'experience_years' => $this->experience_years,
            'certification' => $this->certification,
            'bio' => $this->bio,
            'education' => $this->education,
            'consultation_fee_online' => $this->consultation_fee_online,
            'consultation_fee_inperson' => $this->consultation_fee_inperson,
            'specialization_name' => $this->specialization->name,
            'specialization_description' => $this->specialization->description,
            'location' => $this->location,
            'user' => new UserResource($this->user),

        ];
    }
}
