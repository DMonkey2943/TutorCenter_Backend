<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('users', UserController::class);

//PARENTS
Route::get('parents/getAll', [Parent1Controller::class, 'getAll']);
Route::get('parents/user/{userId}', [Parent1Controller::class, 'getParentByUserId']);
Route::resource('parents', Parent1Controller::class);

// TUTORS
Route::post('tutors/createAccount', [TutorController::class, 'createAccount']);
Route::get('tutors/user/{userId}', [TutorController::class, 'getTutorByUserId']);
Route::post('tutors/available', [TutorController::class, 'getAvailableTutors']);
Route::patch('tutors/{id}/approve', [TutorController::class, 'approveProfile']);
Route::get('/tutors/{id}/averageRating', [TutorController::class, 'getAverageRating']);
Route::match(['patch', 'post'], '/tutors/{id}', [TutorController::class, 'update']);
Route::resource('tutors', TutorController::class);

// CLASSES
Route::get('classes/get12Classes', [Class1Controller::class, 'get12Classes']);
Route::get('classes/getAllNewClasses', [Class1Controller::class, 'getAllNewClasses']);
Route::get('classes/getEnrolledClasses', [Class1Controller::class, 'getEnrolledClasses'])->middleware('auth:sanctum');
Route::get('classes/getConfirmedClasses', [Class1Controller::class, 'getConfirmedClasses'])->middleware('auth:sanctum');
Route::patch('classes/confirmClassTeaching/{classId}', [Class1Controller::class, 'confirmClassTeaching'])->middleware('auth:sanctum');
Route::get('classes/getRegisteredClasses', [Class1Controller::class, 'getRegisterdClasses'])->middleware('auth:sanctum');
Route::patch('classes/completeClass/{classId}', [Class1Controller::class, 'completeClass'])->middleware('auth:sanctum');
Route::resource('classes', Class1Controller::class);

// OTHERS
Route::get('/districts', [DistrictController::class, 'index'])->name('districts.index');
Route::get('wards/getAllBelongToDistrict/{id}', [WardController::class, 'getAllBelongToDistrict']);
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/levels', [LevelController::class, 'index'])->name('levels.index');
Route::get('/tuitions', [TuitionController::class, 'index'])->name('tuitions.index');

// RATES
Route::get('/rates/{classId}', [RateController::class, 'show']);
Route::post('/rates', [RateController::class, 'store']);

// AUTH
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('account/changePassword', 'changePassword')->middleware('auth:sanctum');
    Route::get('account', 'profile')->middleware('auth:sanctum');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});

// APPROVE
Route::controller(ApproveController::class)->group(function () {
    Route::post('approval/enroll', 'store')->middleware('auth:sanctum');
    Route::delete('approval/unenroll/{classId}', 'destroy')->middleware('auth:sanctum');
    Route::patch('approval/{classId}', 'update')->middleware('auth:sanctum');
});

// RATES
Route::post('/reports', [ReportController::class, 'store']);
