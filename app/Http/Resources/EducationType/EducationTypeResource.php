<?php

namespace App\Http\Resources\EducationType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'organization_id' => $this->organization_id ?? null,
            'is_active' => $this->is_active ?? null,
        ];
    }
}
