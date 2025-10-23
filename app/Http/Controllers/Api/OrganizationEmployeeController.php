<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Requests\DeleteOrganizationEmployeeDetailsRequest;
use App\Http\Requests\FetchOrganizationEmployeeDetailsRequest;
use App\Http\Requests\FetchOrganizationEmployeeRequest;
use App\Http\Requests\OrganizationEmployeeLoginRequest;
use App\Http\Requests\OrganizationEmployeeRequest;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UpdateOrganizationEmployeeRequest;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Models\OrganizationEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
/**
 * @OA\Tag(
 *     name="Organization - Employees",
 *     description="Endpoints for managing organization employees (إنشاء وتحديث وحذف واستعراض الموظفين)"
 * )
 */
class OrganizationEmployeeController extends Controller
{

  /**
 * @OA\Post(
 *     path="/login_organization_employee",
 *     operationId="organizationEmployeeLogin",
 *     tags={"Organization Employees"},
 *     summary="تسجيل دخول موظف",
 *     description="تسجيل دخول موظف",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"credentials","password"},
 *             @OA\Property(property="credentials", type="string", example="employee@example.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="تم تسجيل الدخول بنجاح",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="تم تسجيل الدخول بنجاح"),
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/OrganizationEmployeeResource"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="بيانات الدخول غير صحيحة",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="بيانات الدخول غير صحيحة")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="خطأ في التحقق من البيانات",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="خطأ في التحقق من البيانات"),
 *             @OA\Property(property="errors", type="object", example={
 *                 "email": {"الحقل البريد الإلكتروني مستخدم مسبقًا"},
 *                 "password": {"كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل"}
 *             })
 *         )
 *     )
 * )
 */



    public function login(OrganizationEmployeeLoginRequest $request)
    {
        $data = $request->validated();
        $credentials = $data['credentials'];
        $organization_employee = filter_var($credentials, FILTER_VALIDATE_EMAIL)
            ? OrganizationEmployee::where('email', $credentials)->first()
            : OrganizationEmployee::where('phone', $credentials)->first();
        if (!$organization_employee || !Hash::check($data['password'], $organization_employee->password)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }
        $token = $organization_employee->createToken('organization-employee-token')->plainTextToken;
        return ApiResponseHelper::response(
            true,
            'تم تسجيل الدخول بنجاح',
            ['organization_employee' => new OrganizationEmployeeResource($organization_employee, $token)],

        );
    }


    /**
     * @OA\Post(
     *     path="/create_organization_employee",
     *     operationId="createOrganizationEmployee",
     *     tags={"Organization Employees"},
     *     summary="إنشاء موظف جديد",
     *     description="إضافة موظف جديد إلى النظام داخل مؤسسة محددة",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "phone", "email", "password", "type"},
     *             @OA\Property(property="name", type="string", example="أحمد محمد"),
     *             @OA\Property(property="phone", type="string", example="+201000000000"),
     *             @OA\Property(property="email", type="string", example="ahmed@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="image", type="string", example="uploads/employees/avatar.jpg", nullable=true),
     *             @OA\Property(property="type", type="integer", example=1, description="1 = Teacher, 2 = Assistant"),
     *             @OA\Property(property="organization_id", type="integer", example=1, description="معرّف المؤسسة التي ينتمي إليها الموظف"),
     *             @OA\Property(property="parent_id", type="integer", example=1, nullable=true, description="إذا كان مساعدًا تابعًا لمعلم")
     *         )
     *     ),

     *     @OA\Response(
     *         response=201,
     *         description="تم إنشاء الموظف بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء المدرس بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/OrganizationEmployeeResource")
     *         )
     *     ),

     *     @OA\Response(
     *         response=422,
     *         description="بيانات غير صالحة",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="بيانات الإدخال غير صالحة"),
     *             @OA\Property(property="errors", type="object", example={
     *                 "email": {"الحقل البريد الإلكتروني مستخدم مسبقًا"},
     *                 "password": {"كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل"}
     *             })
     *         )
     *     )
     * )
     */

    public function createOrganizationEmployee(OrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        $teacher = OrganizationEmployee::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'type' => $data['type'],
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء المدرس بنجاح', $teacher);
    }


    public function updateOrganizationEmployee(UpdateOrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        $teacher = OrganizationEmployee::find($data['organization_employee_id']);
        $teacher->update([
            'name' => $data['name'] ?? $teacher->name,
            'email' => $data['email'] ?? $teacher->email,
            'phone' => $data['phone'] ?? $teacher->phone,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث المدرس بنجاح', $teacher);
    }



    public function fetchOrganizationEmployees(FetchOrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        $organization_employees = OrganizationEmployee::where('type', $data['type'])->get();
        return ApiResponseHelper::response(true, 'تم جلب المدرسين بنجاح', $organization_employees);
    }

    public function fetchOrganizationEmployeeDetails(FetchOrganizationEmployeeDetailsRequest $request)
    {
        $data = $request->validated();
        $organization_employee = OrganizationEmployee::find($data['organization_employee_id']);
        return ApiResponseHelper::response(true, 'تم جلب بيانات المدرس بنجاح', [
            $organization_employee,
        ]);
    }
    public function deleteOrganizationEmployee(DeleteOrganizationEmployeeDetailsRequest $request)
    {
        $data = $request->validated();
        $organization_employee = OrganizationEmployee::find($data['organization_employee_id']);
        $organization_employee->delete();
        return ApiResponseHelper::response(true, 'تم حذف المدرس بنجاح', $organization_employee);
    }
}
