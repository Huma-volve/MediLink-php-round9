<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HelpItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'faq_url' => $this->faq_url,
            'contact_support_url' => $this->contact_support_url,
            'documentation_url' => $this->documentation_url,
            'video_tutorials_url' => $this->video_tutorials_url,
        ];
    }
}
