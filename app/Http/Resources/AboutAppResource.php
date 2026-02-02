<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutAppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'app_name' => $this->app_name,
            'app_version' => $this->app_version,
            'company_name' => $this->company_name,
            'terms_url' => $this->terms_url,
            'privacy_url' => $this->privacy_url,
            'license_url' => $this->license_url,
            'release_notes_url' => $this->release_notes_url,
        ];
    }
}
