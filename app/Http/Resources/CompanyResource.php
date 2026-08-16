<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'slug' => $this->slug,
            'registration_number' => $this->registration_number,
            'registered_at' => $this->registered_at?->toDateString(),
            'province' => $this->province,
            'business_type' => $this->business_type,
            'description' => $this->description,
            'website_url' => $this->website_url,
            'published' => $this->published,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
