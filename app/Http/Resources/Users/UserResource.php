<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Sessions\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Ali Ahmed"),
 *     @OA\Property(property="email", type="string", example="ali@example.com"),
 *     @OA\Property(property="phone", type="string", example="+201234567890"),
 *     @OA\Property(property="parent_name", type="string", nullable=true, example="Ahmed Hassan"),
 *     @OA\Property(property="parent_phone", type="string", nullable=true, example="+201098765432"),
 *     @OA\Property(property="organization_id", type="integer", example=2),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://example.com/storage/profile.jpg"),
 *     @OA\Property(property="type", type="string", example="student"),
 *     @OA\Property(property="country_code", type="string", example="+20"),
 *     @OA\Property(property="is_verified", type="boolean", example=true),
 *     @OA\Property(property="api_token", type="string", example="1|abcdef1234567890"),
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'parent_name' => $this->parent_name ?? null,
            'parent_phone' => $this->parent_phone ?? null,
            'organization_id' => $this->organization_id ?? null,
            'image' => $this->image ?? null,
            'type' => $this->type->value ?? null,
            'country_code' => $this->country_code ?? null,
            'is_verified' => $this->is_verified ?? null,
            'api_token'=>$this->api_token ?? '',
            'password'=>$this->password ?? '',
        ];
    }
}
