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

class JoinCourseController extends Controller
{
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

    public function fetchJoinCourses()
    {
        $organization = auth()->guard('organization')->user();
        $join_courses = JoinCourseRequest::where('organization_id', $organization->id)->get();
        return ApiResponseHelper::response(true, 'تم جلب [join_courses] التسجيلات بنجاح', [
            'join_courses' => JoinCourseResource::collection($join_courses),
        ]);
    }
    public function showJoinCourse(ShowJoinCourseRequest $request){
        $data = $request->validated();
        $join_course = JoinCourseRequest::find($data['join_course_request_id']);
        return ApiResponseHelper::response(true, 'تم جلب [join_course] التسجيل بنجاح', [
            'join_course' => new JoinCourseResource($join_course),
        ]);
    }

    public function statusJoinCourse(ChangeStatusJoinCourseRequest $request){
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
    public function fetchMyCourses(){
        $user = auth()->user();
        $user_courses = $user->userCourses;
        return ApiResponseHelper::response(true, 'تم جلب [join_courses] التسجيلات بنجاح', [
            'join_courses' => CourseResource::collection($user_courses),
        ]);
    }

    public function showJoinCourseDetails(ShowJoinCourseDetailsRequest $request){
        $data = $request->validated();
        $join_course = JoinCourseRequest::find($data['course_id']);
        return ApiResponseHelper::response(true, 'تم جلب [join_course] التسجيل بنجاح', [
            'join_course' => new JoinCourseResource($join_course),
        ]);
    }

}
