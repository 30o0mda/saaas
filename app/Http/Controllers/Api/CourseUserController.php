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


class CourseUserController extends Controller
{

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
