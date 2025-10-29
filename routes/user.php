<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EducationTypeController;
use App\Http\Controllers\Api\JoinCourseController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\OrganizationEmployeeController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Models\EducationType;
use App\Models\User;
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

Route::post('/register_user', [UserController::class, 'registerUser']);
Route::post('/login_user', [UserController::class, 'loginUser']);
Route::middleware(['auth:user'])->group(function () {
Route::post('/fetch_education_types', [UserController::class, 'fetchEducationTypes']);
Route::post('/fetch_stage_by_education_type', [UserController::class, 'fetchStageByEducationType']);
Route::post('/fetch_stage_children', [UserController::class, 'fetchStageChildren']);
Route::post('/set_user_stage', [UserController::class, 'setUserStage']);
Route::get('/fetch_courses', [UserController::class, 'fetchCourses']);

// join course
Route::post('/join_course', [JoinCourseController::class, 'joinCourse']);
Route::get('/fetch_my_join_courses', [JoinCourseController::class, 'fetchMyCourses']);
Route::post('/show_join_course_details', [JoinCourseController::class, 'showJoinCourseDetails']);



});



