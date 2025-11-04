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

       public function login( $data)
    {
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
            ['organization_employee' => new OrganizationEmployeeResource($organization_employee, $token)]
        );
    }


     public function createOrganizationEmployee( $data)
    {
        $organization = auth()->guard('organization')->user();
        $employee = OrganizationEmployee::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'type' => $data['type'],
            'organization_id' => $organization->id ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'image' => $data['image'] ?? null,
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }

        public function updateOrganizationEmployee( $data)
    {
        $employee = OrganizationEmployee::find($data['organization_employee_id']);
        if (!$employee) {
            return ApiResponseHelper::response(false, 'الموظف غير موجود', null, 404);
        }
        $employee->update([
            'name' => $data['name'] ?? $employee->name,
            'email' => $data['email'] ?? $employee->email,
            'phone' => $data['phone'] ?? $employee->phone,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }

     public function fetchOrganizationEmployees( $data)
    {
        $employees = OrganizationEmployee::where('type', $data['type'])->get();
        return ApiResponseHelper::response(true, 'تم جلب الموظفين بنجاح', OrganizationEmployeeResource::collection($employees));
    }


      public function fetchOrganizationEmployeeDetails( $data)
    {
        $employee = OrganizationEmployee::find($data['organization_employee_id']);
        if (!$employee) {
            return ApiResponseHelper::response(false, 'الموظف غير موجود', null, 404);
        }
        return ApiResponseHelper::response(true, 'تم جلب بيانات الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }


        public function deleteOrganizationEmployee( $data)
    {
        $employee = OrganizationEmployee::find($data['organization_employee_id']);
        if (!$employee) {
            return ApiResponseHelper::response(false, 'الموظف غير موجود', null, 404);
        }
        $employee->delete();
        return ApiResponseHelper::response(true, 'تم حذف الموظف بنجاح', new OrganizationEmployeeResource($employee));
    }

}

