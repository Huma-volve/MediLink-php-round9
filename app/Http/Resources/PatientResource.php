<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->user->name,
            "email" => $this->user->email,
            "profile_picture" => $this->user->profile_picture,
            "phone" => $this->user->phone,
            "insurance company" => $this->insurance->name,
            "date of birth" => $this->date_of_birth,
            "blood type" => $this->blood_group,
            "emergency contact name" => $this->emergency_contact_name,
            "emergency contact phone" => $this->emergency_contact_phone,
            "emergency contact relationship" => $this->emergency_contact_relationship,
        ];
    }
}
