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
use App\Service\JoinCourse\JoinCourseService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="JoinCourse",
 *     description="API Endpoints for joining courses"
 * )
 */
class JoinCourseController extends Controller
{

    protected $JoinCourseService;

    public function __construct(JoinCourseService $JoinCourseService)
    {
        $this->JoinCourseService = $JoinCourseService;
    }


    /**
     * @OA\Post(
     *     path="/join_course",
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
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourseResource")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function joinCourse(CreateJoinCourseRequest $request)
    {
        $data = $request->validated();
        return $this->JoinCourseService->joinCourse($data)->getData();
    }

    /**
     * @OA\Get(
     *     path="/fetch_join_courses",
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
     *                 @OA\Property(property="join_courses", type="array", @OA\Items(ref="#/components/schemas/JoinCourseResource"))
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function fetchJoinCourses()
    {
        return $this->JoinCourseService->fetchJoinCourses()->getData();
    }

    /**
     * @OA\Post(
     *     path="/show_join_course",
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
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourseResource")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function showJoinCourse(ShowJoinCourseRequest $request)
    {
        $data = $request->validated();
        return $this->JoinCourseService->showJoinCourse($data)->getData();
    }

    /**
     * @OA\Post(
     *     path="/status_join_course",
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
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourseResource")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function statusJoinCourse(ChangeStatusJoinCourseRequest $request)
    {
        $data = $request->validated();
        return $this->JoinCourseService->statusJoinCourse($data)->getData();
    }

    /**
     * @OA\Get(
     *     path="/fetch_my_join_courses",
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
     *                 @OA\Property(property="join_courses", type="array", @OA\Items(ref="#/components/schemas/CourseResource"))
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function fetchMyCourses()
    {
        return $this->JoinCourseService->fetchMyCourses()->getData();
    }

    /**
     * @OA\Post(
     *     path="/show_join_course_details",
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
     *                 @OA\Property(property="join_course", ref="#/components/schemas/JoinCourseResource")
     *             )
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
        public function showJoinCourseDetails(ShowJoinCourseDetailsRequest $request){
            $data = $request->validated();
            return $this->JoinCourseService->showJoinCourseDetails($data)->getData();
        }

}
