<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\{
    DeleteOrganizationEmployeeDetailsRequest,
    FetchOrganizationEmployeeDetailsRequest,
    FetchOrganizationEmployeeRequest,
    OrganizationEmployeeLoginRequest,
    OrganizationEmployeeRequest,
    UpdateOrganizationEmployeeRequest
};
use App\Http\Resources\OrganizationEmployeeResource;
use App\Models\OrganizationEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Service\OrganizationEmployee\OrganizationEmployeeService;

/**
 * @OA\Tag(
 *     name="Organization Employees",
 *     description="Endpoints for managing organization employees (إنشاء وتحديث وحذف واستعراض الموظفين)"
 * )
 */
class OrganizationEmployeeController extends Controller
{

    protected $OrganizationEmployeeService;
    public function __construct( OrganizationEmployeeService $organizationEmployeeService)
    {
        $this->OrganizationEmployeeService = $organizationEmployeeService;
    }

    /**
     * @OA\Post(
     *     path="/api/login_organization_employee",
     *     operationId="organizationEmployeeLogin",
     *     tags={"Organization Employees"},
     *     summary="تسجيل دخول موظف",
     *     description="يسمح لموظف المؤسسة بتسجيل الدخول باستخدام البريد الإلكتروني أو رقم الهاتف.",
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
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationEmployeeResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="بيانات الدخول غير صحيحة",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="بيانات الدخول غير صحيحة")
     *         )
     *     )
     * )
     */
    public function login(OrganizationEmployeeLoginRequest $request)
    {
        $data = $request->validated();
        return $this->OrganizationEmployeeService->login($data)->getData();
    }


    /**
     * @OA\Post(
     *     path="/api/create_organization_employee",
     *     operationId="createOrganizationEmployee",
     *     tags={"Organization Employees"},
     *     summary="إنشاء موظف جديد",
     *     security={{"bearerAuth":{}}},
     *     description="إضافة موظف جديد إلى النظام داخل مؤسسة محددة.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "phone", "email", "password", "type", "organization_id"},
     *             @OA\Property(property="name", type="string", example="أحمد محمد"),
     *             @OA\Property(property="phone", type="string", example="+201000000000"),
     *             @OA\Property(property="email", type="string", example="ahmed@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="image", type="string", nullable=true, example="uploads/employees/avatar.jpg"),
     *             @OA\Property(property="type", type="integer", example=1, description="1 = Teacher, 2 = Assistant"),
     *             @OA\Property(property="organization_id", type="integer", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="تم إنشاء الموظف بنجاح",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationEmployeeResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="بيانات غير صالحة"
     *     )
     * )
     */
    public function createOrganizationEmployee(OrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        return $this->OrganizationEmployeeService->createOrganizationEmployee($data)->getData();
    }


    /**
     * @OA\post(
     *     path="/api/update_organization_employee",
     *     operationId="updateOrganizationEmployee",
     *     tags={"Organization Employees"},
     *     summary="تحديث بيانات موظف",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"organization_employee_id"},
     *             @OA\Property(property="organization_employee_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="محمد علي"),
     *             @OA\Property(property="email", type="string", example="m.ali@example.com"),
     *             @OA\Property(property="phone", type="string", example="+201000111222")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تحديث الموظف بنجاح",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationEmployeeResource")
     *     ),
     *     @OA\Response(response=404, description="الموظف غير موجود")
     * )
     */
    public function updateOrganizationEmployee(UpdateOrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        return $this->OrganizationEmployeeService->updateOrganizationEmployee($data)->getData();
    }


    /**
     * @OA\post(
     *     path="/api/fetch_organization_employees",
     *     operationId="fetchOrganizationEmployees",
     *     tags={"Organization Employees"},
     *     summary="عرض جميع الموظفين حسب النوع",
     *     description="جلب جميع الموظفين من نوع معين (مدرسين أو مساعدين).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="type", in="query", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="تم جلب الموظفين بنجاح")
     * )
     */
    public function fetchOrganizationEmployees(FetchOrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        return $this->OrganizationEmployeeService->fetchOrganizationEmployees($data)->getData();
    }


    /**
     * @OA\post(
     *     path="/api/fetch_organization_employee_details",
     *     operationId="fetchOrganizationEmployeeDetails",
     *     tags={"Organization Employees"},
     *     summary="عرض تفاصيل موظف",
     *     description="عرض بيانات موظف محدد من خلال معرفه.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="organization_employee_id", in="query", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="تم جلب بيانات الموظف بنجاح"),
     *     @OA\Response(response=404, description="الموظف غير موجود")
     * )
     */
    public function fetchOrganizationEmployeeDetails(FetchOrganizationEmployeeDetailsRequest $request)
    {
        $data = $request->validated();
        return $this->OrganizationEmployeeService->fetchOrganizationEmployeeDetails($data)->getData();
    }


    /**
     * @OA\post(
     *     path="/api/delete_organization_employee",
     *     operationId="deleteOrganizationEmployee",
     *     tags={"Organization Employees"},
     *     summary="حذف موظف",
     *     description="حذف موظف من النظام باستخدام المعرّف الخاص به.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"organization_employee_id"},
     *             @OA\Property(property="organization_employee_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="تم حذف الموظف بنجاح"),
     *     @OA\Response(response=404, description="الموظف غير موجود")
     * )
     */
    public function deleteOrganizationEmployee(DeleteOrganizationEmployeeDetailsRequest $request)
    {
        $data = $request->validated();
        return $this->OrganizationEmployeeService->deleteOrganizationEmployee($data)->getData();
    }
}
