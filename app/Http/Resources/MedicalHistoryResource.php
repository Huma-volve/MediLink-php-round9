<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "chronic_conditions" => $this->chronic_conditions,
            "allergies" => $this->allergies,
            "previous_surgeries" => $this->previous_surgeries,
            "doctor" => [
                "id" => $this->doctor?->id,
                "name" => $this->doctor?->user?->name,
            ],
            "prescription" => [
                "id" => $this->prescription?->id,
                "prescription_number" => $this->prescription?->prescription_number,
            ],
        ];
    }
}
