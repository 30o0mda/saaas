<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\CreateCourseRequest;
use App\Http\Requests\Courses\DeleteCourseRequest;
use App\Http\Requests\Courses\FetchAllCoursesRequest;
use App\Http\Requests\Courses\FetchCourseRequest;
use App\Http\Requests\Courses\IsActiveRequest;
use App\Http\Requests\Courses\orderCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Resources\Courses\CourseResource;
use App\Models\course;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Courses",
 *     description="Operations related to courses"
 * )
 */
class CourseController extends Controller
{

    /**
 * @OA\Post(
 *     path="/create_course",
 *     operationId="createCourse",
 *     tags={"Courses"},
 *     summary="إنشاء كورس جديد",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","description","stage_and_subject_id","price","is_free"},
 *             @OA\Property(property="name", type="string", example="Mathematics 101"),
 *             @OA\Property(property="description", type="string", example="Basic Math course for beginners"),
 *             @OA\Property(property="stage_and_subject_id", type="integer", example=2),
 *             @OA\Property(property="price", type="number", format="float", example=100.5),
 *             @OA\Property(property="is_free", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم إنشاء الكورس بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم إنشاء الكورس بنجاح"),
 *             @OA\Property(property="data", ref="#/components/schemas/CourseResource")
 *         )
 *     )
 * )
 */
    public function createCourse(CreateCourseRequest $request) {
        $data = $request->validated();
        $order = course::where('organization_id', auth()->user()->organization_id)->max('order') + 1;
        $course = course::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'organization_id' => auth()->user()->organization_id,
            // 'is_active' => $data['is_active'],
            'order' => $order,
            'stage_and_subject_id' => $data['stage_and_subject_id'],
            'price' => $data['price'],
            'is_free' => $data['is_free'],
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }


/**
 * @OA\post(
 *     path="/update_course",
 *     operationId="updateCourse",
 *     tags={"Courses"},
 *     summary="تحديث بيانات كورس",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"course_id"},
 *             @OA\Property(property="course_id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Updated Course Name"),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="is_active", type="boolean", example=true),
 *             @OA\Property(property="order", type="integer", example=3),
 *             @OA\Property(property="stage_and_subject_id", type="integer", example=2),
 *             @OA\Property(property="price", type="number", format="float", example=120.5),
 *             @OA\Property(property="is_free", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم تحديث الكورس بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم تحديث الكورس بنجاح"),
 *             @OA\Property(property="data", ref="#/components/schemas/CourseResource")
 *         )
 *     )
 * )
 */
    public function updateCourse(UpdateCourseRequest $request) {
        $data = $request->validated();
        $course = course::find($data['course_id']);
        $course->update([
            'name' => $data['name'] ?? $course->name,
            'description' => $data['description'] ?? $course->description,
            'is_active' => $data['is_active'] ?? $course->is_active,
            'order' => $data['order'] ?? $course->order,
            'stage_and_subject_id' => $data['stage_and_subject_id'] ?? $course->stage_and_subject_id,
            'price' => $data['price'] ?? $course->price,
            'is_free' => $data['is_free'] ?? $course->is_free,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }


/**
 * @OA\post(
 *     path="/fetch_course_details",
 *     operationId="fetchCourseDetails",
 *     tags={"Courses"},
 *     summary="جلب تفاصيل كورس محدد",
 *     @OA\Parameter(
 *         name="course_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم جلب الكورس بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم جلب الكورس بنجاح"),
 *             @OA\Property(property="data", ref="#/components/schemas/CourseResource")
 *         )
 *     )
 * )
 */
    public function fetchCourseDetails(FetchCourseRequest $request) {
        $data = $request->validated();
        $course = course::find($data['course_id']);
        return ApiResponseHelper::response(true, 'تم جلب الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }

    /**
 * @OA\post(
 *     path="/delete_course",
 *     operationId="deleteCourse",
 *     tags={"Courses"},
 *     summary="حذف كورس",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"course_id"},
 *             @OA\Property(property="course_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم حذف الكورس بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم حذف الكورس بنجاح"),
 *             @OA\Property(property="data", ref="#/components/schemas/CourseResource")
 *         )
 *     )
 * )
 */


        public function deleteCourse(DeleteCourseRequest $request) {
        $data = $request->validated();
        $course = course::find($data['course_id']);
        $course->delete();
        return ApiResponseHelper::response(true, 'تم حذف الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }


/**
 * @OA\Post(
 *     path="/is_active",
 *     operationId="toggleCourseActive",
 *     tags={"Courses"},
 *     summary="تغيير حالة التفعيل لكورس",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"course_id"},
 *             @OA\Property(property="course_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم تغيير حالة الكورس بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم تغيير حالة الكورس بنجاح"),
 *             @OA\Property(property="data", ref="#/components/schemas/CourseResource")
 *         )
 *     )
 * )
 */

    public function isActive(IsActiveRequest $request) {
        $data = request()->validated();
        $course = course::find($data['course_id']);
        $is_active = $course->is_active;
        $course->update([
            'is_active' => !$is_active
        ]);
        return ApiResponseHelper::response(true, 'تم تغيير حالة الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }


/**
 * @OA\Post(
 *     path="/order_course",
 *     operationId="orderCourse",
 *     tags={"Courses"},
 *     summary="تغيير ترتيب الكورس",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"course_id","order"},
 *             @OA\Property(property="course_id", type="integer", example=1),
 *             @OA\Property(property="order", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم تغيير ترتيب الكورس بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم تغيير ترتيب الكورس بنجاح"),
 *             @OA\Property(property="data", ref="#/components/schemas/CourseResource")
 *         )
 *     )
 * )
 */

    public function orderCourse(orderCourseRequest $request) {
        $data = request()->validated();
        $course = course::find($data['course_id']);
        $course->update([
            'order' => $data['order']
        ]);
        Course::where('organization_id', auth()->user()->organization_id)->where('order', '>=', $data['order'])->increment('order');
        return ApiResponseHelper::response(true, 'تم تغيير ترتيب الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }

    /**
 * @OA\post(
 *     path="/fetch_all_courses",
 *     operationId="fetchAllCourses",
 *     tags={"Courses"},
 *     summary="جلب جميع الكورسات",
 *     @OA\Parameter(
 *         name="word",
 *         in="query",
 *         description="للبحث باسم الكورس",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="with_paginate",
 *         in="query",
 *         description="تفعيل أو إيقاف pagination (1 أو 0)",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="عدد النتائج لكل صفحة في حالة pagination",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم جلب جميع الكورسات بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم جلب جميع الكورسات بنجاح"),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CourseResource"))
 *         )
 *     )
 * )
 */

    public function fetchAllCourses(FetchAllCoursesRequest $request) {
        $data = $request->validated();
        $query = Course::where('organization_id', auth()->user()->organization_id);

        if(isset($data['word'])) {
            $query->where('name', 'like', '%' . $data['word'] . '%');
        }

        $query->orderBy('order', 'asc');

        if(isset($data['with_paginate']) && $data['with_paginate'] == 1) {
            $per_page = isset($data['limit']) ? $data['limit'] : 10;
            $all_course = $query->paginate($per_page);
            $response = CourseResource::collection($all_course)->response()->getData(true);
        }else {
            $all_course = $query->get();
            $response = CourseResource::collection($all_course);
        }
        return ApiResponseHelper::response(true, 'تم جلب جميع الكورسات بنجاح', $response);
    }
}
