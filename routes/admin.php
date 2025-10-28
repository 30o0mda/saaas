<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EducationTypeController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\OrganizationEmployeeController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Models\EducationType;
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
Route::prefix('admin')->group(function () {

    // Not Auth Routes
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Auth Routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::post('/create_organization', action: [OrganizationController::class, 'createOrganization']);
        Route::get('/get_organization', [OrganizationController::class, 'getOrganizations']);
    });
});

