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
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Stages",
 *     description="Operations related to stages"
 * )
 */

class StageController extends Controller
{

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
        $data['organization_id'] = auth()->user()->organization_id ?? null;
        $data['parent_id'] = $data['parent_id'] ?? null;
        $stage = stage::create([
            'name' => $data['name'],
            'education_type_id' => $data['education_type_id'],
            'organization_id' => $data['organization_id'],
            'parent_id' => $data['parent_id'],
        ]);
        if(!empty($data['subject_ids'])) {
            $stage->subjects()->attach($data['subject_ids']);
        }
        return ApiResponseHelper::response(true, 'تم إنشاء المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
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
        $stage = stage::find($data['stage_id']);
        $stage->update([
            'name' => $data['name'] ?? $stage->name,
            'education_type_id' => $data['education_type_id'] ?? $stage->education_type_id,
            'organization_id' => $data['organization_id'] ?? $stage->organization_id,
            'parent_id' => $data['parent_id'] ?? $stage->parent_id,
        ]);
        if(!empty($data['subject_ids'])) {
            $stage->subjects()->sync($data['subject_ids']);
        }
        return ApiResponseHelper::response(true, 'تم تحديث المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
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
        $stage = stage::find($data['stage_id']);
        return ApiResponseHelper::response(true, 'تم جلب المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
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
        $stage = stage::find($data['stage_id']);
        $stage->delete();
        return ApiResponseHelper::response(true, 'تم حذف المرحلة بنجاح');
    }
}
