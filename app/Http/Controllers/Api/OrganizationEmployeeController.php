<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Requests\DeleteOrganizationEmployeeDetailsRequest;
use App\Http\Requests\FetchOrganizationEmployeeDetailsRequest;
use App\Http\Requests\FetchOrganizationEmployeeRequest;
use App\Http\Requests\OrganizationEmployeeLoginRequest;
use App\Http\Requests\OrganizationEmployeeRequest;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UpdateOrganizationEmployeeRequest;
use App\Models\OrganizationEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizationEmployeeController extends Controller
{
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
        $token = $organization_employee->createToken('admin-token')->plainTextToken;
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'organization_employee' => $organization_employee
        ]);
    }
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
        return response()->json([
            'message' => 'تم إنشاء المدرس بنجاح',
            'teacher' => $teacher,
        ]);
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
        return response()->json([
            'message' => 'تم تحديث المدرس بنجاح',
            'teacher' => $teacher,
        ]);
    }



    public function fetchOrganizationEmployees(FetchOrganizationEmployeeRequest $request)
    {
        $data = $request->validated();
        $organization_employees = OrganizationEmployee::where('type', $data['type'])->get();
        return response()->json([
            'organization_employees' => $organization_employees,
        ]);
    }

    public function fetchOrganizationEmployeeDetails(FetchOrganizationEmployeeDetailsRequest $request)
    {
        $data = $request->validated();
        $organization_employee = OrganizationEmployee::find($data['organization_employee_id']);
        return response()->json([
            'organization_employee' => $organization_employee,
        ]);
    }

    public function deleteOrganizationEmployee(DeleteOrganizationEmployeeDetailsRequest $request)
    {
        $data = $request->validated();
        $organization_employee = OrganizationEmployee::find($data['organization_employee_id']);
        $organization_employee->delete();
        return response()->json([
            'message' => 'تم حذف المدرس بنجاح',
        ]);
    }
}
