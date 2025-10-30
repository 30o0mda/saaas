<?php

namespace App\Http\Resources\Answers;

use App\Http\Enum\QuestionsEnum\DifficultyQuestionEnum;
use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use App\Http\Enum\QuestionsEnum\QuestionsTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations\MediaType;
use SebastianBergmann\Diff\Diff;

class AnswersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'answer' => $this->answer ?? null,
            'is_correct' => $this->is_correct ?? null,
            'media' => $this->media ?? null,
            'media_type' => $this->media_type
        ];
    }
}
