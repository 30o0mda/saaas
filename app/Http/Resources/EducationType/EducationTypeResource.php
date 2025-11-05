<?php

namespace App\Http\Resources\EducationType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="EducationTypeResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="رياضيات"),
 *     @OA\Property(property="description", type="string", example="دروس تعليمية للمرحلة الإعدادية"),
 *     @OA\Property(property="organization_id", type="integer", example=5),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 */
class EducationTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'organization_id' => $this->organization_id ?? null,
            'is_active' => $this->is_active ?? null,
        ];
    }
}
