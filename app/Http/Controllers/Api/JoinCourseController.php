<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\JoinCourseEnum;
use App\Http\Requests\JoinCourse\ChangeStatusJoinCourseRequest;
use App\Http\Requests\JoinCourse\CreateJoinCourseRequest;
use App\Http\Requests\JoinCourse\ShowJoinCourseDetailsRequest;
use App\Http\Requests\JoinCourse\ShowJoinCourseRequest;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\JoinCourse\JoinCourseResource;
use App\Models\CourseUser;
use App\Models\JoinCourseRequest;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="JoinCourse",
 *     description="API Endpoints for joining courses"
 * )
 */
class JoinCourseController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/join-course",
     *     operationId="joinCourse",
     *     tags={"JoinCourse"},
     *     summary="User joins a course",
     *     description="Allows authenticated user to request joining a course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"course_id", "account_number", "image"},
     *                 @OA\Property(property="course_id", type="integer", example=1),
     *                 @OA\Property(property="account_number", type="string", example="123456789"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Join course request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourse")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function joinCourse(CreateJoinCourseRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $image = $data['image']->store('join_courses', 'public');
        $join_course = JoinCourseRequest::create([
            'user_id' => $user->id,
            'course_id' => $data['course_id'],
            'organization_id' => $user->organization_id,
            'status' => JoinCourseEnum::PENDING->value,
            'account_number' => $data['account_number'],
            'image' => $image,
        ]);
        return ApiResponseHelper::response(true, 'تم التسجيل بنجاح', [
            'join_course' => new JoinCourseResource($join_course),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/join-course",
     *     operationId="fetchJoinCourses",
     *     tags={"JoinCourse"},
     *     summary="Fetch join course requests for organization",
     *     @OA\Response(
     *         response=200,
     *         description="List of join course requests",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب [join_courses] التسجيلات بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="join_courses", type="array", @OA\Items(ref="#/components/schemas/JoinCourse"))
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function fetchJoinCourses()
    {
        $organization = auth()->guard('organization')->user();
        $join_courses = JoinCourseRequest::where('organization_id', $organization->id)->get();
        return ApiResponseHelper::response(true, 'تم جلب [join_courses] التسجيلات بنجاح', [
            'join_courses' => JoinCourseResource::collection($join_courses),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/join-course/show",
     *     operationId="showJoinCourse",
     *     tags={"JoinCourse"},
     *     summary="Show details of a join course request",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"join_course_request_id"},
     *             @OA\Property(property="join_course_request_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Join course request details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب [join_course] التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourse")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function showJoinCourse(ShowJoinCourseRequest $request)
    {
        $data = $request->validated();
        $join_course = JoinCourseRequest::find($data['join_course_request_id']);
        return ApiResponseHelper::response(true, 'تم جلب [join_course] التسجيل بنجاح', [
            'join_course' => new JoinCourseResource($join_course),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/join-course/status",
     *     operationId="statusJoinCourse",
     *     tags={"JoinCourse"},
     *     summary="Change status of a join course request",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"join_course_request_id","status"},
     *             @OA\Property(property="join_course_request_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="ACCEPTED")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تغيير حالة التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourse")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function statusJoinCourse(ChangeStatusJoinCourseRequest $request)
    {
        $data = $request->validated();
        $join_course = JoinCourseRequest::find($data['join_course_request_id']);
        $join_course->update([
            'status' => $data['status']
        ]);
        if($data['status'] == JoinCourseEnum::ACCEPTED->value){
            CourseUser::firstOrCreate([
                'user_id' => $join_course->user_id,
                'course_id' => $join_course->course_id
            ]);
        }
        return ApiResponseHelper::response(true, 'تم تغيير حالة التسجيل بنجاح', [
            'join_course' => new JoinCourseResource($join_course),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/join-course/my-courses",
     *     operationId="fetchMyCourses",
     *     tags={"JoinCourse"},
     *     summary="Fetch authenticated user's joined courses",
     *     @OA\Response(
     *         response=200,
     *         description="List of user's courses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب [join_courses] التسجيلات بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="join_courses", type="array", @OA\Items(ref="#/components/schemas/Course"))
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function fetchMyCourses()
    {
        $user = auth()->user();
        $user_courses = $user->userCourses;
        return ApiResponseHelper::response(true, 'تم جلب [join_courses] التسجيلات بنجاح', [
            'join_courses' => CourseResource::collection($user_courses),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/join-course/details",
     *     operationId="showJoinCourseDetails",
     *     tags={"JoinCourse"},
     *     summary="Show details of a joined course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"course_id"},
     *             @OA\Property(property="course_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Join course details fetched",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب [join_course] التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourse")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function showJoinCourseDetails(ShowJoinCourseDetailsRequest $request){
        $data = $request->validated();
        $join_course = JoinCourseRequest::find($data['course_id']);
        return ApiResponseHelper::response(true, 'تم جلب [join_course] التسجيل بنجاح', [
            'join_course' => new JoinCourseResource($join_course),
        ]);
    }

}
