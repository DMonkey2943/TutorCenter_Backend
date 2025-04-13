<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Parent1Controller;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\Class1Controller;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\WardController;

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

// PUBLIC ROUTES 
Route::get('/districts', [DistrictController::class, 'index'])->name('districts.index');
Route::get('/districts/{districtId}/wards', [WardController::class, 'index']);
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/levels', [LevelController::class, 'index'])->name('levels.index');
Route::get('/tuitions', [TuitionController::class, 'index'])->name('tuitions.index');


// AUTH
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('account/password', 'changePassword');
        Route::get('account', 'profile');
        Route::post('logout', 'logout');
    });
});


// USERS ROUTES
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');


// CLASSES ROUTES
Route::controller(Class1Controller::class)->group(function () {
    Route::get('classes/latest', 'get12Classes');
    Route::get('classes/new', 'getAllNewClasses');

    Route::middleware('auth:sanctum')->group(function () {
        // for tutors
        Route::get('classes/recommended', 'recommendClasses');
        Route::get('classes/enrolled', 'getEnrolledClasses');
        Route::get('classes/confirmed', 'getConfirmedClasses');
        Route::patch('classes/{classId}/confirm', 'confirmClassTeaching');

        // for parents
        Route::get('classes/registered', 'getRegisterdClasses');
        Route::patch('classes/{classId}/complete', 'completeClass');
    });
});
Route::apiResource('classes', Class1Controller::class)->middleware('auth:sanctum');


// PARENTS ROUTES
Route::controller(Parent1Controller::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('parents/getAll', 'getAll');
        Route::get('parents/user/{userId}', 'getParentByUserId');
    });
});
Route::apiResource('parents', Parent1Controller::class)->middleware('auth:sanctum');


// TUTORS ROUTES
Route::controller(TutorController::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        // Route::post('tutors/createAccount', 'createAccount');
        Route::get('tutors/user/{userId}', 'getTutorByUserId');
        Route::post('tutors/available', 'getAvailableTutors'); // for parents
        Route::patch('tutors/{id}/approve', 'approveProfile');
        Route::get('tutors/{id}/rating', 'getAverageRating');
        Route::match(['patch', 'post'], 'tutors/{id}', 'update');
    });
});
Route::apiResource('tutors', TutorController::class)->middleware('auth:sanctum');


// APPROVAL ROUTES
Route::controller(ApproveController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('approval/classes/{classId}', 'index');
    Route::post('approval/classes', 'store'); //tutor enroll class
    Route::patch('approval/classes/{classId}', 'update');
    Route::delete('approval/classes/{classId}', 'destroy'); //tutor unenroll class
});


// RATES ROUTES
Route::controller(RateController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/rates/classes/{classId}', 'show');
    Route::post('rates/classes', 'store');
});


// REPORTS ROUTES
Route::controller(ReportController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/reports/classes/{classId}', 'getTutorReportsForClass');
});
Route::apiResource('reports', ReportController::class)->middleware('auth:sanctum');


//DASHBOARD ADMIN
Route::controller(AdminDashboardController::class)->middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/dashboard/stats', 'getStats');
});