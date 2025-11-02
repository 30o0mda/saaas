<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\JoinCourseEnum;
use App\Http\Requests\JoinCourse\AcceptAndRejectJoinCourseRequest;
use App\Models\CourseOfUser;
use App\Models\CourseUser;
use App\Models\JoinCourseRequest;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="CourseUser",
 *     description="API Endpoints for managing course join requests"
 * )
 */
class CourseUserController extends Controller
{
        /**
     * @OA\Post(
     *     path="/api/course-user/accept",
     *     operationId="acceptJoinCourse",
     *     tags={"CourseUser"},
     *     summary="Accept a user's course join request",
     *     description="Accept a join course request for the authenticated organization",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"join_course_request_id"},
     *             @OA\Property(property="join_course_request_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request accepted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم قبول التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function acceptJoinCourse(AcceptAndRejectJoinCourseRequest $request)
    {
        $data = $request->validated();
        $organization = auth()->guard('organization')->user();
        $joinCourse = JoinCourseRequest::where('organization_id', $organization->id)->find($data['join_course_request_id']);
        $joinCourse->update([
            'status' => JoinCourseEnum::ACCEPTED->value,
            'organization_id' => $organization->id
        ]);
        CourseUser::firstOrCreate([
            'user_id' => $joinCourse->user_id,
            'course_id' => $joinCourse->course_id
        ]);
        return ApiResponseHelper::response(true, 'تم قبول التسجيل بنجاح');
    }

    /**
     * @OA\Post(
     *     path="/api/course-user/reject",
     *     operationId="rejectJoinCourse",
     *     tags={"CourseUser"},
     *     summary="Reject a user's course join request",
     *     description="Reject a join course request for the authenticated organization",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"join_course_request_id"},
     *             @OA\Property(property="join_course_request_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request rejected successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم رفض التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function rejectJoinCourse(AcceptAndRejectJoinCourseRequest $request)
    {
        $data = $request->validated();
        $organization = auth()->guard('organization')->user();
        $joinCourse = JoinCourseRequest::where('organization_id', $organization->id)->find($data['join_course_request_id']);
        $joinCourse->update([
            'status' => JoinCourseEnum::REJECTED->value,
            'organization_id' => $organization->id
        ]);
        return ApiResponseHelper::response(true, 'تم رفض التسجيل بنجاح');
    }
}
