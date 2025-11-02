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

/**
 * @OA\Tag(
 *     name="QuestionBank",
 *     description="API Endpoints for Question Banks"
 * )
 */
class QuestionBankController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/question-bank",
     *     operationId="createQuestionBank",
     *     tags={"QuestionBank"},
     *     summary="Create a new question bank",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","stage_and_subject_id","price","image"},
     *                 @OA\Property(property="name", type="string", example="بنك الرياضيات"),
     *                 @OA\Property(property="description", type="string", example="وصف بنك الاسئلة"),
     *                 @OA\Property(property="stage_and_subject_id", type="integer", example=1),
     *                 @OA\Property(property="price", type="number", example=50),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Question bank created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم انشاء بنك الاسئله بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="question_bank", ref="#/components/schemas/QuestionBank")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function createQuestionBank(CreateQuestionBankRequest $request)
    {
        $data = $request->validated();
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

    /**
     * @OA\Post(
     *     path="/api/question-bank/update",
     *     operationId="updateQuestionBank",
     *     tags={"QuestionBank"},
     *     summary="Update an existing question bank",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"question_bank_id","name","stage_and_subject_id","price"},
     *             @OA\Property(property="question_bank_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="بنك الرياضيات المعدل"),
     *             @OA\Property(property="description", type="string", example="وصف جديد"),
     *             @OA\Property(property="stage_and_subject_id", type="integer", example=2),
     *             @OA\Property(property="price", type="number", example=75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Question bank updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث بنك الاسئله بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="question_bank", ref="#/components/schemas/QuestionBank")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function updateQuestionBank(UpdateQuestionBankRequest $request)
    {
        $data = $request->validated();
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

    /**
     * @OA\Get(
     *     path="/api/question-bank",
     *     operationId="fetchQuestionBank",
     *     tags={"QuestionBank"},
     *     summary="Fetch all question banks for organization",
     *     @OA\Parameter(
     *         name="word",
     *         in="query",
     *         description="Search keyword",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="with_paginate",
     *         in="query",
     *         description="Enable pagination (1 for yes)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of question banks",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب جميع بنك الاسئله بنجاح"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function fetchQuestionBank(FetchQuestionBankRequest $request)
    {
        $data = $request->validated();
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

    /**
     * @OA\Post(
     *     path="/api/question-bank/show",
     *     operationId="showQuestionBankDetails",
     *     tags={"QuestionBank"},
     *     summary="Show question bank details",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"question_bank_id"},
     *             @OA\Property(property="question_bank_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Question bank details fetched",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم عرض بنك الاسئله بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="question_bank", ref="#/components/schemas/QuestionBank")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function ShowQuestionBankDetails(ShowQuestionBankRequest $request){
        $data = $request->validated();
        $questionBank = QuestionBank::find($data['question_bank_id']);
        return ApiResponseHelper::response(true, 'تم عرض بنك الاسئله بنجاح', [
            'question_bank' => new QuestionBankResource($questionBank),
        ]);
    }
/**
 * @OA\Delete(
 *     path="/api/question-bank/delete",
 *     operationId="deleteQuestionBank",
 *     tags={"QuestionBank"},
 *     summary="Delete a question bank",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"question_bank_id"},
 *             @OA\Property(property="question_bank_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Question bank deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم حذف بنك الاسئله بنجاح"),
 *             @OA\Property(property="data", type="array", example=[])
 *         )
 *     ),
 *     security={{"sanctum": {}}}
 * )
 */
public function deleteQuestionBank(Request $request){
    $data = $request->all();
    $questionBank = QuestionBank::find($data['question_bank_id']);
    $questionBank->delete();
    return ApiResponseHelper::response(true, 'تم حذف بنك الاسئله بنجاح', []);
}

/**
 * @OA\Post(
 *     path="/api/question-bank/toggle-status",
 *     operationId="toggleStatusQuestionBank",
 *     tags={"QuestionBank"},
 *     summary="Toggle the active status of a question bank",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"question_bank_id"},
 *             @OA\Property(property="question_bank_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Question bank status toggled successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم تغيير حالة بنك الاسئله بنجاح"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="question_bank", ref="#/components/schemas/QuestionBank")
 *             )
 *         )
 *     ),
 *     security={{"sanctum": {}}}
 * )
 */
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
