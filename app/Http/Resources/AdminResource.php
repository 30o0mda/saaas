<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
    * @OA\Schema(
    *     schema="AdminResource",
    *     type="object",
    *     title="Admin",
    *     description="Admin Resource",
    *     @OA\Property(property="id", type="integer", example="1"),
    *     @OA\Property(property="name", type="string", example="Ahmed Emad"),
    *     @OA\Property(property="email", type="string", format="email", example="employee@example.com"),
    *     @OA\Property(property="phone", type="string", example="+201234567890"),
    *     @OA\Property(property="type", type="text", example="مدير"),
    *     @OA\Property(property="image", type="text", example="default.png"),
    *     @OA\Property(property="token", type="string", example="jdsjdfhsdfjbsdbdfjdfbsdfjkfbjsjdh"),
    * )
     */

class AdminResource extends JsonResource
{
    protected $token;

    public function __construct($resource,$token=null)
    {
        parent::__construct($resource);
        $this->token = $token;

    }


    public function toArray(Request $request): array
    {
        // dd($this);
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone,
            'type' => $this->type->label(),
            'image' => $this->image,
            'token'=>$this->token ?? '',
            'api_token'=>$this->api_token ?? ''
        ];
    }
}
