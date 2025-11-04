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
use App\Service\Question\QuestionService;
use Illuminate\Http\Request;
use OpenApi\Annotations\MediaType;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService) {
        $this->questionService = $questionService;
    }


    public function createQuestion(CreateQuestionRequest $request) {
        $data = $request->validated();
        return $this->questionService->createQuestion($data)->getData();
    }
}
