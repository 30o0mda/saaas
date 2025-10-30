<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionBank\CreateQuestionBankRequest;
use App\Http\Requests\QuestionBank\FetchQuestionBankRequest;
use App\Http\Requests\QuestionBank\IsActiveQuestionBankRequest;
use App\Http\Requests\QuestionBank\ShowQuestionBankRequest;
use App\Http\Requests\QuestionBank\ToggleStatusQuestionBankRequest;
use App\Http\Requests\QuestionBank\UpdateQuestionBankRequest;
use App\Http\Resources\QuestionBank\QuestionBankResource;
use App\Models\QuestionBank;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    public function createQuestionBank(CreateQuestionBankRequest $request)
    {
        $data = $request->validated();
            $organization = auth()->guard('organization')->user();
        $image = $data['image']->store('question_banks', 'public');
        $order = QuestionBank::where('organization_id', $organization->id)->max('order');
        $order = $order ? $order + 1 : 1;
        $questionBank =  QuestionBank::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'stage_and_subject_id' => $data['stage_and_subject_id'],
            'image' => $image,
            'price' => $data['price'],
            'organization_id' => $organization->id,
            'order' => $order,
        ]);
        $questionBank->refresh();
        return ApiResponseHelper::response(true, 'تم انشاء بنك الاسئله بنجاح', [
            'question_bank' => new QuestionBankResource($questionBank),
        ]);
    }

    public function updateQuestionBank(UpdateQuestionBankRequest $request){
        $data = $request->validated();
        $questionBank = QuestionBank::find($data['question_bank_id']);
        $questionBank->update([
            'name' => $data['name'] ? $data['name'] : $questionBank->name,
            'description' => $data['description'] ? $data['description'] : $questionBank->description,
            'stage_and_subject_id' => $data['stage_and_subject_id'] ? $data['stage_and_subject_id'] : $questionBank->stage_and_subject_id,
            'price' => $data['price'] ? $data['price'] : $questionBank->price,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث بنك الاسئله بنجاح', [
            'question_bank' => new QuestionBankResource($questionBank),
        ]);
    }

    public function fetchQuestionBank(FetchQuestionBankRequest $request){
        $data = $request->validated();
        $query = QuestionBank::where('organization_id', auth()->user()->organization_id);
        if (isset($data['word'])) {
            $query->where('name', 'like', '%' . $data['word'] . '%');
        }
        $query->orderBy('order', 'asc');
        if (isset($data['with_paginate']) && $data['with_paginate'] == 1) {
            $per_page = isset($data['limit']) ? $data['limit'] : 10;
            $all_question_bank = $query->paginate($per_page);
            $response = QuestionBankResource::collection($all_question_bank)->response()->getData(true);
        } else {
            $all_question_bank = $query->get();
            $response = QuestionBankResource::collection($all_question_bank);
        }
        return ApiResponseHelper::response(true, 'تم جلب جميع بنك الاسئله بنجاح', $response);
    }
    public function ShowQuestionBankDetails(ShowQuestionBankRequest $request){
        $data = $request->validated();
        $questionBank = QuestionBank::find($data['question_bank_id']);
        return ApiResponseHelper::response(true, 'تم عرض بنك الاسئله بنجاح', [
            'question_bank' => new QuestionBankResource($questionBank),
        ]);
    }
    public function deleteQuestionBank(Request $request){
        $data = $request->all();
        $questionBank = QuestionBank::find($data['question_bank_id']);
        $questionBank->delete();
        return ApiResponseHelper::response(true, 'تم حذف بنك الاسئله بنجاح', []);
    }

        public function toggleStatusQuestionBank(ToggleStatusQuestionBankRequest $request){
            $data = $request->validated();
            $questionBank = QuestionBank::find($data['question_bank_id']);
            $questionBank->update([
                'is_active' => !$questionBank->is_active
            ]);
            return ApiResponseHelper::response(true, 'تم تغيير حالة بنك الاسئله بنجاح', [
                'question_bank' => new QuestionBankResource($questionBank),
            ]);
        }
}
