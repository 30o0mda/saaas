<?php

namespace App\Service\Admin;

use App\Models\Admin;
use App\Http\Resources\AdminResource;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponseHelper;
class AdminService
{
    public function __construct()
    {
    }
      public function login( $params)
    {
        $admin = $params['email'] !== null
            ? Admin::where('email', $params['email'])->first()
            : Admin::where('phone', $params['phone'])->first();
        if (!$admin || !Hash::check($params['password'], $admin->password)) {
            return ApiResponseHelper::response(false, 'بيانات الدخول غير صحيحة');
        }
        $token = $admin->createToken('admin-token')->plainTextToken;
        $admin['api_token'] = $token;
        return ApiResponseHelper::response(true, 'تم تسجيل الدخول بنجاح', [
            'admin' => new AdminResource($admin, $token),

        ]);
    }
}
