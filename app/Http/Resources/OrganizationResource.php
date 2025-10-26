<?php

namespace App\Http\Resources;

use App\Http\Enum\OrganizationEmployeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 *     schema="OrganizationResource",
 *     type="object",
 *     title="OrganizationEmployeeResource",
 *     required={"id","name","email","phone","type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="أحمد المعلم"),
 *     @OA\Property(property="email", type="string", format="email", example="teacher@example.com"),
 *     @OA\Property(property="phone", type="string", example="+201234567890"),
 *     @OA\Property(property="type", type="string", example="معلم"),
 *     @OA\Property(property="is_master", type="integer", example=2),
 *     @OA\Property(property="admin_id", type="integer", example=1)
 * )
 */

class OrganizationResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type->label(),
            'image' => $this->image,
            'max_teachers'=>$this->max_teachers,
            'admin_id'=>$this->admin_id,
            'teachers'=> OrganizationEmployeeResource::collection($this->organization_employee()->where('type',OrganizationEmployeEnum::TEACHER->value)->get()),
            'assistants'=> OrganizationEmployeeResource::collection($this->organization_employee()->where('type',OrganizationEmployeEnum::ASSISTANT->value)->get()),
        ];
    }
}
