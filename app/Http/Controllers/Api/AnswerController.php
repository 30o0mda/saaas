<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use App\Http\Requests\Answers\CreateAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function createAnswer(CreateAnswerRequest $request) {
        $data = $request->validated();
        $organization = auth()->guard('organization')->user();
        $answer = Answer::create([
            'answer' => $data['answer'],
            'question_id' => $data['question_id'],
            'media' => $data['media'],
            'media_type' => MediaTypeEnum::from($data['media_type'])->value,
            'is_correct' => $data['is_correct'],
            'organization_id' => $organization->id
        ]);
    }
}
