<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\OrganizationEmployeeController;
use App\Http\Controllers\Api\TeacherController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::post('/create_organization', action: [OrganizationController::class, 'createOrganization']);
    Route::get('/get_organization', [OrganizationController::class, 'getOrganizations']);
});

Route::post('/login_organization_employee', [OrganizationEmployeeController::class, 'login']);
Route::middleware(['auth:organization','check_if_master'])->group(function () {
    Route::post('/create_organization_employee', [OrganizationEmployeeController::class, 'createOrganizationEmployee']);
    Route::post('/update_organization_employee', [OrganizationEmployeeController::class, 'updateOrganizationEmployee']);
    Route::post('/fetch_organization_employees', [OrganizationEmployeeController::class, 'fetchOrganizationEmployees']);
    Route::post('/fetch_organization_employee_details', [OrganizationEmployeeController::class, 'fetchOrganizationEmployeeDetails']);
    Route::post('/delete_organization_employee', [OrganizationEmployeeController::class, 'deleteOrganizationEmployee']);
});
