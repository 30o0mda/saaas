<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{

// public function login(LoginRequest $request)
// {
//     $data = $request->validated();
//     $admin = Admin::where('email', $data['email'])->first();
//     if (!$admin && !Hash::check($data['password'], $admin->password)) {
//         return response()->json(['massage' => 'success'],401);
//     }
//     $token = $admin->createToken('admin-token')->plainTextToken;
//     return response()->json(['massage' => 'success','token' => $token,'admin' => $admin]);
// }

public function login(LoginRequest $request)
{
    $data = $request->validated();
    $credentials = $data['credentials'];
    $admin = filter_var($credentials, FILTER_VALIDATE_EMAIL)
        ? Admin::where('email', $credentials)->first()
        : Admin::where('phone', $credentials)->first();
    if (!$admin || !Hash::check($data['password'], $admin->password)) {
        return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
    }
    $token = $admin->createToken('admin-token')->plainTextToken;
    return response()->json([
        'message' => 'تم تسجيل الدخول بنجاح',
        'token' => $token,
        'admin' => $admin
    ]);
}
}


