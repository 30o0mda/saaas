<?php

namespace App\Http\Resources\Stages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
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
            'education_type_id' => $this->education_type_id ?? null,
            'organization_id' => $this->organization_id ?? null,
            'parent_id' => $this->parent_id ? (int)$this->parent_id : null,
        ];
    }
}
