<?php

namespace App\Service\OrganizationEmployee;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\OrganizationEmployeeLoginRequest;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Models\OrganizationEmployee;
use Illuminate\Support\Facades\Hash;


class OrganizationEmployeeService
{
    public function __construct()
    {
    }

       public function login( $params)
    {
        $organization_employee = $params['email'] !== null
            ? OrganizationEmployee::where('email', $params['email'])->first()
            : OrganizationEmployee::where('phone', $params['phone'])->first();
        if (!$organization_employee || !Hash::check($params['password'], $organization_employee->password)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }
        $token = $organization_employee->createToken('organization-employee-token')->plainTextToken;
        return ApiResponseHelper::response(
            true,
            'تم تسجيل الدخول بنجاح',
            ['organization_employee' => new OrganizationEmployeeResource($organization_employee, $token)]
        );
    }


     public function createOrganizationEmployee( $params)
    {
        $employee = OrganizationEmployee::create($params);
        return ApiResponseHelper::response(true, 'تم إنشاء الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }

        public function updateOrganizationEmployee( $params)
    {
        $employee = OrganizationEmployee::find($params['organization_employee_id']);
        if (!$employee) {
            return ApiResponseHelper::response(false, 'الموظف غير موجود', null, 404);
        }
        $employee->update([
            'name' => $params['name'] ?? $employee->name,
            'email' => $params['email'] ?? $employee->email,
            'phone' => $params['phone'] ?? $employee->phone,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }

     public function fetchOrganizationEmployees( $params)
    {
        $employees = OrganizationEmployee::where('type', $params['type'])->get();
        return ApiResponseHelper::response(true, 'تم جلب الموظفين بنجاح', OrganizationEmployeeResource::collection($employees));
    }


      public function fetchOrganizationEmployeeDetails( $params)
    {
        $employee = OrganizationEmployee::find($params['organization_employee_id']);
        if (!$employee) {
            return ApiResponseHelper::response(false, 'الموظف غير موجود', null, 404);
        }
        return ApiResponseHelper::response(true, 'تم جلب بيانات الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }


        public function deleteOrganizationEmployee( $params)
    {
        $employee = OrganizationEmployee::find($params['organization_employee_id']);
        if (!$employee) {
            return ApiResponseHelper::response(false, 'الموظف غير موجود', null, 404);
        }
        $employee->delete();
        return ApiResponseHelper::response(true, 'تم حذف الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }

}

