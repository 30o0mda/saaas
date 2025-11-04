<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stages\CreateStageRequest;
use App\Http\Requests\Stages\DeleteStageRequest;
use App\Http\Requests\Stages\FetchStageRequest;
use App\Http\Requests\Stages\UpdateStageRequest;
use App\Http\Resources\Stages\StageResource;
use App\Models\stage;
use App\Models\StageAndSubject;
use App\Service\Stage\StageService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Stages",
 *     description="Operations related to stages"
 * )
 */

class StageController extends Controller
{
    protected $StageService;

    public function __construct(StageService $StageService)
    {
        $this->StageService = $StageService;
    }

    /**
     * @OA\Post(
     *     path="/create_stage",
     *     summary="إنشاء مرحلة جديدة",
     *     tags={"Stages"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","education_type_id"},
     *             @OA\Property(property="name", type="string", example="المرحلة الإعدادية"),
     *             @OA\Property(property="education_type_id", type="integer", example=2),
     *             @OA\Property(property="parent_id", type="integer", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء المرحلة بنجاح"),
     *             @OA\Property(property="stage", ref="#/components/schemas/StageResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطأ في التحقق من البيانات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="حدث خطأ، البيانات غير صالحة"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function createStage(CreateStageRequest $request) {
        $data = $request->validated();
         return $this->StageService->createStage($data)->getData();
    }

        /**
     * @OA\post(
     *     path="/update_stage",
     *     summary="تحديث مرحلة",
     *     tags={"Stages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID الخاص بالمرحلة",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="المرحلة الثانوية"),
     *             @OA\Property(property="education_type_id", type="integer", example=2),
     *             @OA\Property(property="parent_id", type="integer", example=null),
     *             @OA\Property(property="subject_ids", type="array", @OA\Items(type="integer"), example={1,2})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث المرحلة بنجاح"),
     *             @OA\Property(property="stage", ref="#/components/schemas/StageResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطأ في التحقق من البيانات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="حدث خطأ، البيانات غير صالحة"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function updateStage(UpdateStageRequest $request) {
        $data = $request->validated();
        return $this->StageService->updateStage($data)->getData();
    }

        /**
     * @OA\post(
     *     path="/fetch_stage",
     *     summary="جلب مرحلة معينة",
     *     tags={"Stages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب المرحلة بنجاح"),
     *             @OA\Property(property="stage", ref="#/components/schemas/StageResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="المرحلة غير موجودة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="المرحلة غير موجودة")
     *         )
     *     )
     * )
     */

    public function fetchStages(FetchStageRequest $request) {
        $data = $request->validated();
        return $this->StageService->fetchStages($data)->getData();
    }

        /**
     * @OA\post(
     *     path="/delete_stage",
     *     summary="حذف مرحلة",
     *     tags={"Stages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم حذف المرحلة بنجاح")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="المرحلة غير موجودة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="المرحلة غير موجودة")
     *         )
     *     )
     * )
     */

    public function deleteStage(DeleteStageRequest $request) {
        $data = $request->validated();
        return $this->StageService->deleteStage($data)->getData();
    }
}
