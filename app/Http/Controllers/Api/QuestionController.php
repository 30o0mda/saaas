<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\QuestionsEnum\DifficultyQuestionEnum;
use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use App\Http\Enum\QuestionsEnum\QuestionsTypeEnum;
use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Resources\Questions\QuestionsResource;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use OpenApi\Annotations\MediaType;

class QuestionController extends Controller
{
    public function createQuestion(CreateQuestionRequest $request) {
        $data = $request->validated();
        $organization = auth()->guard('organization')->user();
        $question = Question::create([
            'question' => $data['question'],
            'question_bank_id' => $data['question_bank_id'],
            'degree' => $data['degree'],
            'media' => $data['media'],
            'media_type' => MediaTypeEnum::from($data['media_type'])->value,
            'question_type' => QuestionsTypeEnum::from($data['question_type'])->value,
            'difficulty' => DifficultyQuestionEnum::from($data['difficulty'])->value,
            'organization_id' => $organization->id
        ]);
        $question->refresh();
        foreach ($data['answers'] as $answer) {
            Answer::create([
                'answer' => $answer['answer'],
                'question_id' => $question->id,
                'media' => $answer['media'],
                'media_type' => MediaTypeEnum::from($answer['media_type'])->value,
                'is_correct' => $answer['is_correct'],
                'organization_id' => $organization->id
            ]);
        }
        return ApiResponseHelper::response(true, 'تم انشاء سؤال بنجاح', [
            'question' => new QuestionsResource($question),
        ]);
    }
}
