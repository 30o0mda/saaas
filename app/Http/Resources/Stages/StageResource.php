<?php

namespace App\Http\Resources\Stages;

use App\Http\Resources\Subjects\SubjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="StageResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="المرحلة الإعدادية"),
 *     @OA\Property(property="education_type_id", type="integer", example=3),
 *     @OA\Property(property="organization_id", type="integer", example=5),
 *     @OA\Property(property="parent_id", type="integer", example=0, nullable=true)
 * )
 */
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
            'subjects' => SubjectResource::collection($this->subjects)
        ];
    }
}
