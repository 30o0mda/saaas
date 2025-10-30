<?php

namespace App\Http\Resources\Questions;

use App\Http\Enum\QuestionsEnum\DifficultyQuestionEnum;
use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use App\Http\Enum\QuestionsEnum\QuestionsTypeEnum;
use App\Http\Resources\Answers\AnswersResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations\MediaType;
use SebastianBergmann\Diff\Diff;

class QuestionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'question' => $this->question ?? null,
            'degree' => $this->degree ?? null,
            'is_active' => $this->is_active ?? null,
            'question_bank_id' => $this->question_bank_id ?? null,
            'question_type' => $this->question_type->value,
            'difficulty' => $this->difficulty->value,
            'media' => $this->media ?? null,
            'media_type' => $this->media_type->value,
            'answers' => AnswersResource::collection($this->answers)
        ];
    }
}
