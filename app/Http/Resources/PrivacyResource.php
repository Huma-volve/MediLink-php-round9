<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrivacyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'is_visible' => $this->is_visible,
            'is_active' => $this->is_active,
            'data_sharing' => $this->data_sharing,
            'two_factor_auth' => $this->two_factor_auth,
        ];
    }
}
