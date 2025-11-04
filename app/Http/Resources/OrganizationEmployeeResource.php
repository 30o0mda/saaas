<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="OrganizationEmployeeResource",
 *     type="object",
 *     title="Organization Employee",
 *     description="Organization Employee Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Ahmed Emad"),
 *     @OA\Property(property="email", type="string", format="email", example="employee@example.com"),
 *     @OA\Property(property="phone", type="string", example="+201234567890"),
 *     @OA\Property(property="type", type="text", example="مدير"),
 *     @OA\Property(property="image", type="text", example="default.png"),
 *     @OA\Property(property="is_master", type="integer", example="1"),
 *     @OA\Property(property="parent_id", type="integer", example="1"),
 *     @OA\Property(property="organization_id", type="integer", example="1"),
 *     @OA\Property(property="admin_id", type="integer", example="1"),
 *     @OA\Property(property="token", type="string", example="jdsjdfhsdfjbsdbdfjdfbsdfjkfbjsjdh"),
 *
 * )
 */

class OrganizationEmployeeResource extends JsonResource
{

    protected $token;
    public function __construct($resource,$token=null)
    {
        parent::__construct($resource);
        $this->token = $token;
    }


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'type' => $this->type->label() ?? null,
            'image' => $this->image ?? null,
            'is_master' => $this->is_master ?? false,
            'parent_id' => (int)$this->parent_id ?? null,
            'organization_id'=>$this->organization_id ?? null,
            'token' => $this->token ?? null
        ];
    }
}
