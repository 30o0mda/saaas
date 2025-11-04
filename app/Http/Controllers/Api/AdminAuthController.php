<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Params\Admin\LoginParam;
use App\Service\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Operations related to admin"
 * )
 */
class AdminAuthController extends Controller
{


    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     *
     * @OA\Post(
     *     path="/admin/login",
     *     tags={"Admin"},
     *     summary="تسجيل الدخول",
     *     description="تسجيل الدخول للادمن",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"credentials","password"},
     *             @OA\Property(property="credentials", type="string", example="email or phone"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تسجيل الدخول بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تسجيل الدخول بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/AdminResource"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="بيانات الدخول غير صحيحة",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="بيانات الدخول غير صحيحة"),
     *     @OA\Property(property="errors", type="object", example={
     *                 "email": {"الحقل البريد الإلكتروني مستخدم مسبقًا"},
     *                 "password": {"كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل"}
     *             })
     *         )
     *     ),
     * )
     */

    public function login(LoginRequest $request)
    {
        $params = new LoginParam(credentials: $request['credentials'], password: $request['password']);
      return  $this->adminService->login($params->toArray())->getData();
    }
}


