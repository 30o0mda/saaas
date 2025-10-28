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
Route::prefix('organization')->group(function () {
    Route::post('/login_organization_employee', [OrganizationEmployeeController::class, 'login']);
    Route::middleware(['auth:organization', 'check_if_master'])->group(function () {
        Route::post('/create_organization_employee', [OrganizationEmployeeController::class, 'createOrganizationEmployee']);
        Route::post('/update_organization_employee', [OrganizationEmployeeController::class, 'updateOrganizationEmployee']);
        Route::post('/fetch_organization_employees', [OrganizationEmployeeController::class, 'fetchOrganizationEmployees']);
        Route::post('/fetch_organization_employee_details', [OrganizationEmployeeController::class, 'fetchOrganizationEmployeeDetails']);
        Route::post('/delete_organization_employee', [OrganizationEmployeeController::class, 'deleteOrganizationEmployee']);

        // Education type
        Route::post('/create_education_type', [EducationTypeController::class, 'createEducationType']);
        Route::post('/update_education_type', [EducationTypeController::class, 'updateEducationType']);
        Route::post('/fetch_education_types', [EducationTypeController::class, 'fetchEducationTypes']);
        Route::post('/delete_education_type', [EducationTypeController::class, 'deleteEducationType']);

        //Stages
        Route::post('/create_stage', [StageController::class, 'createStage']);
        Route::post('/update_stage', [StageController::class, 'updateStage']);
        Route::post('/fetch_stages', [StageController::class, 'fetchStages']);
        Route::post('/delete_stage', [StageController::class, 'deleteStage']);

        //subject
        Route::post('/create_subject', [SubjectController::class, 'createSubject']);
        Route::post('/update_subject', [SubjectController::class, 'updateSubject']);
        Route::post('/fetch_subjects', [SubjectController::class, 'fetchSubjects']);
        Route::post('/delete_subject', [SubjectController::class, 'deleteSubject']);

        // Courses
        Route::post('/create_course', [CourseController::class, 'createCourse']);
        Route::post('/update_course', [CourseController::class, 'updateCourse']);
        Route::post('/fetch_course_details', [CourseController::class, 'fetchCourseDetails']);
        Route::post('/delete_course', [CourseController::class, 'deleteCourse']);
        Route::post('/is_active', [CourseController::class, 'isActive']);
        Route::post('/order_course', [CourseController::class, 'orderCourse']);
        Route::post('/fetch_all_courses', [CourseController::class, 'fetchAllCourses']);

        // Sessions
        Route::post('/create_session', [SessionController::class, 'createSession']);
        Route::post('/update_session', [SessionController::class, 'updateSession']);
        Route::post('/fetch_session_details', [SessionController::class, 'fetchSessionDetails']);
        Route::post('/delete_session', [SessionController::class, 'deleteSession']);
        Route::post('/is_active_session', [SessionController::class, 'isActiveSession']);
        Route::post('/order_session', [SessionController::class, 'orderSession']);
        Route::post('/fetch_all_sessions', [SessionController::class, 'fetchAllSessions']);
    });
});


