<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\QuestionBank\QuestionBankStatus as QuestionBankQuestionBankStatus;
use App\Http\Enum\QuestionBank\QuestionBankStatus;
use App\Http\Enum\UserEnum;
use App\Http\Requests\StudentResult\StudentResultRequest;
use App\Http\Requests\Users\FetchQuestionBankDetailesRequest;
use App\Http\Requests\Users\FetchQuestionBankRequest;
use App\Http\Requests\Users\FetchStageByEducationTypeRequest;
use App\Http\Requests\Users\FetchStageChildrenRequest;
use App\Http\Requests\Users\JoinQuestionBankRequest;
use App\Http\Requests\Users\SetUserStageRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\EducationType\EducationTypeResource;
use App\Http\Resources\QuestionBank\QuestionBankResource;
use App\Http\Resources\Stages\StageResource;
use App\Http\Resources\StudentResult\StudentResultResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Answer;
use App\Models\course;
use App\Models\EducationType;
use App\Models\organization;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\stage;
use App\Models\StudentResult;
use App\Models\User;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


/**
 * @OA\Tag(
 *     name=" Users",
 *     description="Operations related to users"
 * )
 */
class UserController extends Controller
{

    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    /**
     * @OA\Post(
     *     path="/register_user",
     *     summary="تسجيل مستخدم جديد",
     *     description="يُستخدم لتسجيل مستخدم جديد في النظام",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","phone","country_code"},
     *             @OA\Property(property="name", type="string", example="محمد"),
     *             @OA\Property(property="email", type="string", example="mohamed@test.com"),
     *             @OA\Property(property="phone", type="string", example="01000000000"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="country_code", type="string", example="+20"),
     *             @OA\Property(property="organization_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم التسجيل بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم التسجيل بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="بيانات غير صحيحة")
     * )
     */
    public function registerUser(UserRegisterRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->registerUser($data)->getData();
    }

    /**
     * @OA\Post(
     *     path="/login_user",
     *     summary="تسجيل الدخول",
     *     description="تسجيل الدخول بواسطة البريد أو رقم الهاتف",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"credentials","password"},
     *             @OA\Property(property="credentials", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تسجيل الدخول بنجاح"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="بيانات الدخول غير صحيحة"
     *     )
     * )
     */
    public function loginUser(UserLoginRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->loginUser($data)->getData();
    }

    /**
     * @OA\post(
     *     path="/fetch_education_types",
     *     summary="جلب أنواع التعليم للمستخدم الحالي",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب أنواع التعليم بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب [education_types] نوع التعليم بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="education_types", type="array",
     *                     @OA\Items(ref="#/components/schemas/EducationTypeResource")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function fetchEducationTypes()
    {
        return $this->UserService->fetchEducationTypes()->getData();
    }

    /**
     * @OA\Post(
     *     path="/fetch_stage_by_education_type",
     *     summary="جلب المراحل حسب نوع التعليم",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"education_type_id"},
     *             @OA\Property(property="education_type_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب المراحل بنجاح"
     *     )
     * )
     */


    public function fetchStageByEducationType(FetchStageByEducationTypeRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->fetchStageByEducationType($data)->getData();
    }

    /**
     * @OA\Post(
     *     path="/fetch_stage_children",
     *     summary="جلب مراحل فرعية حسب المرحلة الأب",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"parent_id"},
     *             @OA\Property(property="parent_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب المراحل الفرعية بنجاح"
     *     )
     * )
     */


    public function fetchStageChildren(FetchStageChildrenRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->fetchStageChildren($data)->getData();
    }

    /**
     * @OA\Post(
     *     path="/set_user_stage",
     *     summary="تعيين المرحلة للمستخدم",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"stage_id"},
     *             @OA\Property(property="stage_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تغيير المرحلة بنجاح"
     *     )
     * )
     */


    public function setUserStage(SetUserStageRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->setUserStage($data)->getData();
    }

    /**
     * @OA\Get(
     *     path="/fetch_courses",
     *     summary="جلب الكورسات الخاصة بالمستخدم",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب الكورسات بنجاح"
     *     )
     * )
     */


    public function fetchCourses()
    {
        return $this->UserService->fetchCourses()->getData();
    }

    /**
     * @OA\Post(
     *     path="/fetch_question_bank",
     *     summary="جلب أسئلة بنك معين",
     *     tags={"QuestionBank"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"question_bank_id"},
     *             @OA\Property(property="question_bank_id", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب الأسئلة بنجاح"
     *     )
     * )
     */


    public function fetchQuestionBank(FetchQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->fetchQuestionBank($data)->getData();
    }

    /**
     * @OA\Post(
     *     path="/join_question_bank",
     *     summary="انضمام المستخدم إلى بنك الأسئلة",
     *     tags={"QuestionBank"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","question_bank_id"},
     *             @OA\Property(property="user_id", type="integer", example=3),
     *             @OA\Property(property="question_bank_id", type="integer", example=10),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم الانضمام بنجاح"
     *     )
     * )
     */


    public function joinQuestionBank(JoinQuestionBankRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->joinQuestionBank($data)->getData();
    }

    /**
     * @OA\post(
     *     path="/fetch_question_bank_details",
     *     summary="جلب تفاصيل بنوك الأسئلة الخاصة بالمستخدم",
     *     tags={"QuestionBank"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب بنوك الأسئلة بنجاح"
     *     )
     * )
     */

    public function fetchQuestionBankDetails(FetchQuestionBankDetailesRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();
        $question_bank = $user->questionBanks;
        return ApiResponseHelper::response(true, 'تم جلب [questions] الاسئلة بنجاح', [
            'questions_bank' => QuestionBankResource::collection($question_bank)
        ]);
    }



    /**
     * @OA\Post(
     *     path="/student_result",
     *     summary="حفظ نتيجة إجابة الطالب على سؤال",
     *     tags={"UserResults"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"question_id","answer_id"},
     *             @OA\Property(property="question_id", type="integer", example=15),
     *             @OA\Property(property="answer_id", type="integer", example=44)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم حفظ الإجابة بنجاح"
     *     )
     * )
     */
    public function studentResult(StudentResultRequest $request)
    {
        $data = $request->validated();
        return $this->UserService->studentResult($data)->getData();
    }
}
