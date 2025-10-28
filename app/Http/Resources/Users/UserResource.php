<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Sessions\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'parent_phone' => $this->parent_email ?? null,
            'organization_id' => $this->organization_id ?? null,
            'image' => $this->image ?? null,
            'type' => $this->type->label(),
            'country_code' => $this->country_code ?? null,
            'is_verified' => $this->is_verified ?? null,
            'api_token'=>$this->api_token ?? '',
            'password'=>$this->password ?? '',
        ];
    }
}
