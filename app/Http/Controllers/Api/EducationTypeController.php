<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationType\CreateEducationTypeRequest;
use App\Http\Requests\EducationType\DeleteEducationTypeRequest;
use App\Http\Requests\EducationType\UpdateEducationTypeRequest;
use App\Http\Requests\EducationType\FetchEducationTypeRequest;
use App\Http\Resources\EducationType\EducationTypeResource;
use App\Models\EducationType;
use App\Service\EducationType\EducationTypeService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Education Types",
 *     description="Operations related to education types"
 * )
 */

class EducationTypeController extends Controller
{

    protected $educationTypeService;

    public function __construct(EducationTypeService $educationTypeService)
    {
        $this->educationTypeService = $educationTypeService;
    }
        /**
     * @OA\Post(
     *     path="/create_education_type",
     *     summary="إنشاء نوع تعليم جديد",
     *     tags={"Education Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="تعليم أساسي"),
     *             @OA\Property(property="description", type="string", example="وصف النوع"),
     *             @OA\Property(property="is_active", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء نوع التعليم بنجاح"),
     *             @OA\Property(property="education_type", ref="#/components/schemas/EducationTypeResource")
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
    public function createEducationType(CreateEducationTypeRequest $request) {
        $date = $request->validated();
        return $this->educationTypeService->createEducationType($date)->getData();
    }

        /**
     * @OA\Post(
     *     path="/update_education_type",
     *     summary="تحديث نوع تعليم",
     *     tags={"Education Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"education_type_id"},
     *             @OA\Property(property="education_type_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="تعليم ثانوي"),
     *             @OA\Property(property="description", type="string", example="وصف النوع"),
     *             @OA\Property(property="is_active", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث نوع التعليم بنجاح"),
     *             @OA\Property(property="education_type", ref="#/components/schemas/EducationTypeResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="نوع التعليم غير موجود",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="نوع التعليم غير موجود")
     *         )
     *     )
     * )
     */

    public function updateEducationType(UpdateEducationTypeRequest $request) {
        $date = $request->validated();
        return $this->educationTypeService->updateEducationType($date)->getData();
    }

        /**
     * @OA\Get(
     *     path="/fetch_education_types",
     *     summary="جلب كل أنواع التعليم",
     *     tags={"Education Types"},
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب أنواع التعليم بنجاح"),
     *             @OA\Property(property="education_types", type="array",
     *                 @OA\Items(ref="#/components/schemas/EducationTypeResource")
     *             )
     *         )
     *     )
     * )
     */

    public function fetchEducationTypes(FetchEducationTypeRequest $request) {
        $date = $request->validated();
        return $this->educationTypeService->fetchEducationTypes($date)->getData();
    }

        /**
     * @OA\Post(
     *     path="/delete_education_type",
     *     summary="حذف نوع تعليم",
     *     tags={"Education Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"education_type_id"},
     *             @OA\Property(property="education_type_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="نجاح العملية",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم حذف نوع التعليم بنجاح")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="نوع التعليم غير موجود",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="نوع التعليم غير موجود")
     *         )
     *     )
     * )
     */

    public function deleteEducationType(DeleteEducationTypeRequest $request) {
        $date = $request->validated();
        return $this->educationTypeService->deleteEducationType($date)->getData();
    }
}
