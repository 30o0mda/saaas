<?php

namespace App\Http\Resources\StudentResult;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name ?? null,
            ],
            'organization_id' => $this->organization_id,
            'question_bank' => [
                'id' => $this->question_bank_id,
                'name' => $this->questionBank->name ?? null,
            ],
            'is_finished' => (bool) $this->is_finished,
            'total_questions' => $this->questionBank->questions->count() ?? 0,
            'answered_questions' => $this->studentResultAnswers->count() ?? 0,
            'correct_answers' => $this->studentResultAnswers
                ->where('is_correct', true)
                ->count(),
            'answers' => $this->studentResultAnswers->map(function ($answer) {
                return [
                    'question_id' => $answer->question_id,
                    'question_name' => $answer->question->question ?? null,
                    'answer_id' => $answer->answer_id,
                    'answer_text' => $answer->answer->answer ?? null,
                    'is_correct' => (bool) $answer->is_correct,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
