<?php

namespace App\Http\Resources\Courses;

use App\Http\Resources\Sessions\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="CourseResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Mathematics 101"),
 *     @OA\Property(property="description", type="string", example="Basic Math course"),
 *     @OA\Property(property="order", type="integer", example=1),
 *     @OA\Property(property="stage_and_subject_id", type="integer", example=2),
 *     @OA\Property(property="price", type="number", format="float", example=100.5),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="is_free", type="boolean", example=false),
 *     @OA\Property(property="organization_id", type="integer", example=1)
 * )
 */


class CourseResource extends JsonResource
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
            'order' => $this->order ?? null,
            'stage_and_subject_id' => $this->stage_and_subject_id ?? null,
            'price' => $this->price ?? null,
            'is_active' => $this->is_active ?? false,
            'is_free' => $this->is_free ?? false,
            'organization_id' => $this->organization_id ?? null,
            'sessions' => SessionResource::collection($this?->parent_sessions)??[],
        ];}
}
