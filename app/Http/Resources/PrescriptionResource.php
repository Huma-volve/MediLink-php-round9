<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "prescription_number" => $this->prescription_number,
            "diagnosis" => $this->diagnosis,
            "patient_conditions" => $this->patient_conditions,
            "prescription_date" => $this->prescription_date,
            "expiry_date" => $this->expiry_date,
            "items" => PrescriptionItemResource::collection($this->items), // requires relation
        ];
    }
}
