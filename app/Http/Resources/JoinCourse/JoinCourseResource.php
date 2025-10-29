<?php

namespace App\Http\Resources\JoinCourse;

use App\Http\Enum\JoinCourseEnum;
use App\Http\Resources\Sessions\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JoinCourseResource extends JsonResource
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
            'user_id' => $this->user_id ?? null,
            'course_id' => $this->course_id ?? null,
            'organization_id' => $this->organization_id ?? null,
            'account_number' => $this->account_number ?? null,
            'image' => $this->image ? url('storage/'.$this->image): null,
            'status' => $this->status ?? null,
        ];}
}
