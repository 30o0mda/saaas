<?php

namespace App\Http\Resources;

use App\Http\Enum\OrganizationEmployeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
     * schema="SimpleCreateOrganizationRequest",
     * type="object",
     * title="create organization",
     * description="CreateOrganizationRequest",
     * @OA\property(property="id" , type="integer" , example="1"),
     * @OA\property(property="name" , type="string" , example="Ahmed Emad"),
     * @OA\property(property="email" , type="string" ,format="email" , example="ahmed@example.com"),
     * @OA\property(property="type" , type="text" , example="1"),
     * @OA\property(property="phone" , type="integer" , example="+201000000000"),
     * @OA\property(property="image" , type="text" , format="url" , example="https://example.com/storage/employees/1.png"),
     * @OA\property(property="max_teachers" , type="integer" , example="10"),
     * @OA\property(property="admin_id" , type="integer" , example="1"),
     * @OA\property(property="teachers" , ref="#/components/schemas/OrganizationEmployeeResource"),
     * @OA\property(property="assistants" , ref="#/components/schemas/OrganizationEmployeeResource"),
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
