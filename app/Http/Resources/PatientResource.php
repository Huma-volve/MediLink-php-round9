<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MedicalHistoryResource;
use App\Http\Resources\PrescriptionResource;
use App\Http\Resources\PrescriptionItemResource;

class PatientResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            "id" => $this->id,
            "name" => $this->user?->name,
            "email" => $this->user?->email,
            "profile_picture" => $this->user?->profile_picture,
            "phone" => $this->user?->phone,
            "insurance_company" => $this->insurance?->name,
            "date_of_birth" => $this->date_of_birth,
            "blood_type" => $this->blood_group,
            "emergency_contact_name" => $this->emergency_contact_name,
            "emergency_contact_phone" => $this->emergency_contact_phone,
            "emergency_contact_relationship" => $this->emergency_contact_relationship,
            "medical_histories" => MedicalHistoryResource::collection($this->medicalHistories),
            "prescriptions" => PrescriptionResource::collection($this->prescriptions),

        ];
    }
}
