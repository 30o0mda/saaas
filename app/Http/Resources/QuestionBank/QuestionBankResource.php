<?php

namespace App\Http\Resources\QuestionBank;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'organization_id' => $this->organization_id ?? null,
            'stage_and_subject_id' => $this->stage_and_subject_id ?? null,
            'image' => $this->image ? url('storage/'.$this->image): null,
            'is_active' => (int)$this->is_active ?? false,
            'order' => $this->order ?? null,
            'price' => $this->price ?? null,
        ];
    }
}
