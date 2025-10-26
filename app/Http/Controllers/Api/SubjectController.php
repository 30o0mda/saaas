<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subjects\CreateSubjectRequest;
use App\Http\Requests\Subjects\FetchSubjectRequest;
use App\Http\Requests\Subjects\UpdateSubjectRequest;
use App\Http\Requests\Subjects\DeleteSubjectRequest;
use App\Http\Resources\Subjects\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ٍSubjects",
 *     description="Operations related to subjects"
 * )
 */
class SubjectController extends Controller
{
        /**
     * @OA\Post(
     *     path="/create_subject",
     *     summary="إنشاء موضوع جديد",
     *     tags={"Subjects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description"},
     *             @OA\Property(property="name", type="string", example="الرياضيات"),
     *             @OA\Property(property="description", type="string", example="موضوع دراسي للمرحلة الإعدادية")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم إنشاء الموضوع بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء الموضوع بنجاح"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="subject", ref="#/components/schemas/SubjectResource")
     *             )
     *         )
     *     )
     * )
     */
    public function createSubject(CreateSubjectRequest $request) {
        $data = $request->validated();
        $subject = Subject::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء الموضوع بنجاح', [
            'subject' => new SubjectResource($subject),
        ]);
    }

        /**
     * @OA\post(
     *     path="/update_subject",
     *     summary="تحديث موضوع موجود",
     *     tags={"Subjects"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="الرياضيات"),
     *             @OA\Property(property="description", type="string", example="موضوع محدث")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تحديث الموضوع بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث الموضوع بنجاح"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="subject", ref="#/components/schemas/SubjectResource")
     *             )
     *         )
     *     )
     * )
     */
    public function updateSubject(UpdateSubjectRequest $request) {
        $data = $request->validated();
        $subject = Subject::find($data['subject_id']);
        $subject->update([
            'name' => $data['name'] ?? $subject->name,
            'description' => $data['description'] ?? $subject->description,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الموضوع بنجاح', [
            'subject' => new SubjectResource($subject),
        ]);
    }

        /**
     * @OA\post(
     *     path="/fetch_subject",
     *     summary="جلب موضوع محدد",
     *     tags={"Subjects"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب الموضوع بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب الموضوع بنجاح"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="subject", ref="#/components/schemas/SubjectResource")
     *             )
     *         )
     *     )
     * )
     */

    public function fetchSubject(FetchSubjectRequest $request) {
        $data = $request->validated();
        $subject = Subject::find($data['subject_id']);
        return ApiResponseHelper::response(true, 'تم جلب الموضوع بنجاح', [
            'subject' => new SubjectResource($subject),
        ]);
    }

        /**
     * @OA\post(
     *     path="/delete_subject",
     *     summary="حذف موضوع",
     *     tags={"Subjects"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم حذف الموضوع بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم حذف الموضوع بنجاح")
     *         )
     *     )
     * )
     */

    public function deleteSubject(DeleteSubjectRequest $request) {
        $data = $request->validated();
        $subject = Subject::find($data['subject_id']);
        $subject->delete();
        return ApiResponseHelper::response(true, 'تم حذف الموضوع بنجاح');
    }
}
