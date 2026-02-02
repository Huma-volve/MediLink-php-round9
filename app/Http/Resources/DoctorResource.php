<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'license_number' => $this->license_number,
            'experience_years' => $this->experience_years,
            'certification' => $this->certification,
            'bio' => $this->bio,
            'education' => $this->education,
            'consultation_fee_online' => $this->consultation_fee_online,
            'consultation_fee_inperson' => $this->consultation_fee_inperson,
            'specialization_id' => $this->specialization_id,
            'location' => $this->location,
            'is_verified' => $this->is_verified,
            'average_rating' => round($this->reviews->avg('rating'), 2),
            'review_count' => $this->reviews->count(),
        ];
    }
}
