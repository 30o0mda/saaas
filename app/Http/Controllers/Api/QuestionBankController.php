<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionBank\CreateQuestionBankRequest;
use App\Http\Requests\QuestionBank\DeleteQuestionBankRequest;
use App\Http\Requests\QuestionBank\FetchQuestionBankRequest;
use App\Http\Requests\QuestionBank\IsActiveQuestionBankRequest;
use App\Http\Requests\QuestionBank\ShowQuestionBankRequest;
use App\Http\Requests\QuestionBank\ToggleStatusQuestionBankRequest;
use App\Http\Requests\QuestionBank\UpdateQuestionBankRequest;
use App\Http\Resources\QuestionBank\QuestionBankResource;
use App\Models\QuestionBank;
use App\Service\QuestionBank\QuestionBankService;
use Illuminate\Http\Request;


class QuestionBankController extends Controller
{

    protected $questionBankService;

    public function __construct(QuestionBankService $questionBankService)
    {
        $this->questionBankService = $questionBankService;
    }


    public function createQuestionBank(CreateQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->questionBankService->createQuestionBank($data)->getData();
    }


    public function updateQuestionBank(UpdateQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->questionBankService->updateQuestionBank($data)->getData();
    }

    public function fetchQuestionBank(FetchQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->questionBankService->fetchQuestionBank($data)->getData();
    }


    public function ShowQuestionBankDetails(ShowQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->questionBankService->showQuestionBankDetails($data)->getData();
    }

    public function deleteQuestionBank(DeleteQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->questionBankService->deleteQuestionBank($data)->getData();
    }


    public function toggleStatusQuestionBank(ToggleStatusQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->questionBankService->toggleStatusQuestionBank($data)->getData();
    }
}
