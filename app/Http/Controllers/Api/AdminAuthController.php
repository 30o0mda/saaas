<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
/**
 * @OA\Tag(
 *     name="Organization - Employees",
 *     description="Endpoints for managing organization employees (إنشاء وتحديث وحذف واستعراض الموظفين)"
 * )
 */
class AdminAuthController extends Controller
{

    /**
     *
     * @OA\Post(
     *     path="/admin/login",
     *     tags={"Organization Admins"},
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
        $data = $request->validated();
        $credentials = $data['credentials'];
        $admin = filter_var($credentials, FILTER_VALIDATE_EMAIL)
            ? Admin::where('email', $credentials)->first()
            : Admin::where('phone', $credentials)->first();
        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return ApiResponseHelper::response(false, 'بيانات الدخول غير صحيحة');
        }
        $token = $admin->createToken('admin-token')->plainTextToken;
        $admin['api_token'] = $token;
        return ApiResponseHelper::response(true, 'تم تسجيل الدخول بنجاح', [
            'admin' => new AdminResource($admin, $token),

        ]);
    }
}


