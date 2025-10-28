<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\UserEnum;
use App\Http\Requests\Users\FetchStageByEducationTypeRequest;
use App\Http\Requests\Users\FetchStageChildrenRequest;
use App\Http\Requests\Users\SetUserStageRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\EducationType\EducationTypeResource;
use App\Http\Resources\Stages\StageResource;
use App\Http\Resources\Users\UserResource;
use App\Models\course;
use App\Models\EducationType;
use App\Models\organization;
use App\Models\stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registerUser(UserRegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'country_code' => $data['country_code'],
            'type' => UserEnum::from($data['type'])->value,
            'parent_name' => $data['parent_name'],
            'parent_phone' => $data['parent_phone'],
            'organization_id' => $data['organization_id'],
            'image' => $data['image'],
        ]);
        return ApiResponseHelper::response(true, 'تم التسجيل بنجاح', [
            'user' => new UserResource($user),
        ]);
    }
    public function loginUser(UserLoginRequest $request)
    {
        $data = $request->validated();
        $credentials = $data['credentials'];
        $user = filter_var($credentials, FILTER_VALIDATE_EMAIL)
            ? User::where('email', $credentials)->first()
            : User::where('phone', $credentials)->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ApiResponseHelper::response(false, 'البريد الالكتروني او رقم الجوال غير صحيح');
        }
        if (!$user->hasVerifiedEmail()) {
            return ApiResponseHelper::response(false, 'من فضلك تحقق من بريدك الإلكتروني');
        }
        $token = $user->createToken('user-token')->plainTextToken;
        $user['api_token'] = $token;
        return ApiResponseHelper::response(true, 'تم تسجيل الدخول بنجاح', [
            'user' => new UserResource($user),
        ]);
    }


    public function fetchEducationTypes()
    {
        $education_types = EducationType::where('organization_id', auth()->user()->organization_id)->get();
        return ApiResponseHelper::response(true, 'تم جلب [education_types] نوع التعليم بنجاح', [
            'education_types' => EducationTypeResource::collection($education_types)
        ]);
    }

    public function fetchStageByEducationType(FetchStageByEducationTypeRequest $request)
    {
        $data = $request->validated();
        $stages = stage::where('education_type_id', $data['education_type_id'])->get();
        return ApiResponseHelper::response(true, 'تم جلب [stages] المراحل بنجاح', [
            'stages' => StageResource::collection($stages)
        ]);
    }

    public function fetchStageChildren(FetchStageChildrenRequest $request)
    {
        $data = $request->validated();
        $stages = stage::where('parent_id', $data['parent_id'])->get();
        return ApiResponseHelper::response(true, 'تم جلب [stages] المراحل بنجاح', [
            'stages' => StageResource::collection($stages)
        ]);
    }

    public function setUserStage(SetUserStageRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $user->update([
            'stage_id' => $data['stage_id']
        ]);
        return ApiResponseHelper::response(true, 'تم تغيير المرحلة بنجاح');
    }

    public function fetchCourses()
    {
        $user = auth()->guard('user')->user();
        $course = course::where('organization_id', $user->organization_id)
        ->whereHas('stageAndSubject', function ($query) use ($user) {
            $query->where('stage_id', $user->stage_id);
        })->get();

        return ApiResponseHelper::response(true, 'تم جلب [courses] الكورسات بنجاح', [
            'courses' => CourseResource::collection($course)
        ]);
    }
}


