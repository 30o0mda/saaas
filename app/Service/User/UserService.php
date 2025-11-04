<?php

namespace App\Service\User;

use App\Helpers\ApiResponseHelper;
use App\Http\Enum\QuestionBank\QuestionBankStatus;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\EducationType\EducationTypeResource;
use App\Http\Resources\QuestionBank\QuestionBankResource;
use App\Http\Resources\Stages\StageResource;
use App\Http\Resources\StudentResult\StudentResultResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Answer;
use App\Models\course;
use App\Models\EducationType;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\Stage;
use App\Models\StudentResult;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct() {}

    public function registerUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'country_code' => $data['country_code'],
            'organization_id' => $data['organization_id'] ?? null,
        ]);
        return ApiResponseHelper::response(true, 'تم التسجيل بنجاح', [
            'user' => new UserResource($user),
        ]);
    }


    public function loginUser($data)
    {
        $credentials = $data['credentials'];
        $user = filter_var($credentials, FILTER_VALIDATE_EMAIL)
            ? User::where('email', $credentials)->first()
            : User::where('phone', $credentials)->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ApiResponseHelper::response(false, 'البريد الالكتروني او رقم الجوال غير صحيح');
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

    public function fetchStageByEducationType($data)
    {
        $stages = Stage::where('education_type_id', $data['education_type_id'])->get();
        return ApiResponseHelper::response(true, 'تم جلب [stages] المراحل بنجاح', [
            'stages' => StageResource::collection($stages)
        ]);
    }

    public function fetchStageChildren($data)
    {
        $stages = stage::where('parent_id', $data['parent_id'])->get();
        return ApiResponseHelper::response(true, 'تم جلب [stages] المراحل بنجاح', [
            'stages' => StageResource::collection($stages)
        ]);
    }


    public function setUserStage($data)
    {
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


    public function fetchQuestionBank($data)
    {
        $question_bank = QuestionBank::find($data['question_bank_id']);
        return ApiResponseHelper::response(true, 'تم جلب [questions] الاسئلة بنجاح', [
            'questions_bank' => $question_bank->questions
        ]);
    }

    public function joinQuestionBank($request)
    {
        $data = $request->validated();
        $user = User::find($data['user_id']);
        $questionBank = QuestionBank::find($data['question_bank_id']);
        $status = $data['status'] ?? QuestionBankStatus::PENDING->value;
        if ($status !== QuestionBankStatus::PENDING->value) {
            return ApiResponseHelper::response(false, 'لم يتم قبول الانضمام');
        }
        $user->questionBanks()->syncWithoutDetaching([
            $questionBank->id => [
                'status' => $status
            ]
        ]);
        return ApiResponseHelper::response(true, 'تم الانضمام لبنك الاسئله بنجاح', [
            'status' => $status,
            'question_bank' => $questionBank,
        ]);
    }


    public function fetchQuestionBankDetails()
    {

        $user = auth()->user();
        $question_bank = $user->questionBanks;
        return ApiResponseHelper::response(true, 'تم جلب [questions] الاسئلة بنجاح', [
            'questions_bank' => QuestionBankResource::collection($question_bank)
        ]);
    }



    public function studentResult($data)
    {
        $student = auth()->user();
        $question = Question::find($data['question_id']);
        $questionBank = QuestionBank::find($question->question_bank_id);

        $studentResult = StudentResult::firstOrCreate([
            'user_id' => $student->id,
            'question_bank_id' => $question->question_bank_id,
            'organization_id' => $student->organization_id
        ]);
        if ($studentResult->is_finished) {
            return ApiResponseHelper::response(false, 'لقد انتهى الاختبار');
        }
        $answer = Answer::find($data['answer_id']);
        $studentResult->studentResultAnswers()->create([
            'student_result_id' => $studentResult->id,
            'question_id' => $question->id,
            'answer_id' => $data['answer_id'],
            'is_correct' => $answer->is_correct
        ]);
        $count = $questionBank->questions->count();
        $studentAnswerCount = $studentResult->studentResultAnswers->count();
        if ($count == $studentAnswerCount) {
            $studentResult->update([
                'is_finished' => true
            ]);
        }
        return ApiResponseHelper::response(true, 'تم حفظ الاجابة بنجاح', [
            'result' => new StudentResultResource($studentResult)
        ]);
    }
}
