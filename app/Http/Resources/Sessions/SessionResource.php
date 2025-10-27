<?php

namespace App\Http\Resources\Sessions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="Session",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="name", type="string", example="aaa"),
 *     @OA\Property(property="description", type="string", example="ddd"),
 *     @OA\Property(property="course_id", type="integer", example=1),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="link", type="string", example="ddd"),
 *     @OA\Property(property="file", type="string", example="ddd"),
 *     @OA\Property(property="order", type="integer", example=1),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="type", type="string", example="فيديو"),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Session")
 *     )
 * )
 */

class SessionResource extends JsonResource
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
            'course_id' => $this->course_id ?? null,
            'parent_id' => $this->parent_id ? (int)$this->parent_id : null,
            'link' => $this->link ?? null,
            'file' => $this->file ?? null,
            'order' => $this->order ?? 1,
            'is_active' => $this->is_active ?? 1,
            'type' => $this->type->label() ?? null,
            'children' => SessionResource::collection($this?->children) ?? [],
        ];
    }
}
