<?php

namespace App\Service\QuestionBank;

use App\Helpers\ApiResponseHelper;
use App\Http\Resources\QuestionBank\QuestionBankResource;
use App\Models\QuestionBank;

class QuestionBankService{
    public function __construct()
    {

    }

        public function createQuestionBank( $data)
    {
        $organization = auth()->guard('organization')->user();
        $image = $data['image']->store('question_banks', 'public');
        $order = QuestionBank::where('organization_id', $organization->id)->max('order');
        $order = $order ? $order + 1 : 1;
        $questionBank = QuestionBank::create([
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


       public function updateQuestionBank( $data)
    {
        $questionBank = QuestionBank::find($data['question_bank_id']);
        $questionBank->update([
            'name' => $data['name'] ?? $questionBank->name,
            'description' => $data['description'] ?? $questionBank->description,
            'stage_and_subject_id' => $data['stage_and_subject_id'] ?? $questionBank->stage_and_subject_id,
            'price' => $data['price'] ?? $questionBank->price,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث بنك الاسئله بنجاح', [
            'question_bank' => new QuestionBankResource($questionBank),
        ]);
    }


        public function fetchQuestionBank( $data)
    {
        $query = QuestionBank::where('organization_id', auth()->user()->organization_id);
        if (isset($data['word'])) {
            $query->where('name', 'like', '%' . $data['word'] . '%');
        }
        $query->orderBy('order', 'asc');
        if (isset($data['with_paginate']) && $data['with_paginate'] == 1) {
            $per_page = $data['limit'] ?? 10;
            $all_question_bank = $query->paginate($per_page);
            $response = QuestionBankResource::collection($all_question_bank)->response()->getData(true);
        } else {
            $all_question_bank = $query->get();
            $response = QuestionBankResource::collection($all_question_bank);
        }
        return ApiResponseHelper::response(true, 'تم جلب جميع بنك الاسئله بنجاح', $response);
    }


        public function ShowQuestionBankDetails( $data){
        $questionBank = QuestionBank::find($data['question_bank_id']);
        return ApiResponseHelper::response(true, 'تم عرض بنك الاسئله بنجاح', [
            'question_bank' => new QuestionBankResource($questionBank),
        ]);
    }


    public function deleteQuestionBank( $data){
    $questionBank = QuestionBank::find($data['question_bank_id']);
    $questionBank->delete();
    return ApiResponseHelper::response(true, 'تم حذف بنك الاسئله بنجاح', []);
}


public function toggleStatusQuestionBank( $data){
    $questionBank = QuestionBank::find($data['question_bank_id']);
    $questionBank->update([
        'is_active' => !$questionBank->is_active
    ]);
    return ApiResponseHelper::response(true, 'تم تغيير حالة بنك الاسئله بنجاح', [
        'question_bank' => new QuestionBankResource($questionBank),
    ]);
}
}

