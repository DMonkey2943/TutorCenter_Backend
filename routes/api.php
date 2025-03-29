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

// Route::apiResource('users', UserController::class);

// //PARENTS
// Route::get('parents/getAll', [Parent1Controller::class, 'getAll']);
// Route::get('parents/user/{userId}', [Parent1Controller::class, 'getParentByUserId']);
// Route::apiResource('parents', Parent1Controller::class);

// // TUTORS
// Route::post('tutors/createAccount', [TutorController::class, 'createAccount']);
// Route::get('tutors/user/{userId}', [TutorController::class, 'getTutorByUserId']);
// Route::post('tutors/available', [TutorController::class, 'getAvailableTutors']);
// Route::patch('tutors/{id}/approve', [TutorController::class, 'approveProfile']);
// Route::get('/tutors/{id}/averageRating', [TutorController::class, 'getAverageRating']);
// Route::match(['patch', 'post'], '/tutors/{id}', [TutorController::class, 'update']);
// Route::apiResource('tutors', TutorController::class);

// // CLASSES
// Route::get('classes/get12Classes', [Class1Controller::class, 'get12Classes']);
// Route::get('classes/getAllNewClasses', [Class1Controller::class, 'getAllNewClasses']);
// Route::get('classes/getEnrolledClasses', [Class1Controller::class, 'getEnrolledClasses'])->middleware('auth:sanctum');
// Route::get('classes/getConfirmedClasses', [Class1Controller::class, 'getConfirmedClasses'])->middleware('auth:sanctum');
// Route::patch('classes/confirmClassTeaching/{classId}', [Class1Controller::class, 'confirmClassTeaching'])->middleware('auth:sanctum');
// Route::get('classes/getRegisteredClasses', [Class1Controller::class, 'getRegisterdClasses'])->middleware('auth:sanctum');
// Route::patch('classes/completeClass/{classId}', [Class1Controller::class, 'completeClass'])->middleware('auth:sanctum');
// Route::get('classes/recommendClasses', [Class1Controller::class, 'recommendClasses'])->middleware('auth:sanctum');
// Route::apiResource('classes', Class1Controller::class);



// // RATES
// Route::get('/rates/{classId}', [RateController::class, 'show']);
// Route::post('/rates', [RateController::class, 'store']);

// // APPROVE
// Route::controller(ApproveController::class)->group(function () {
//     Route::post('approval/enroll', 'store')->middleware('auth:sanctum');
//     Route::delete('approval/unenroll/{classId}', 'destroy')->middleware('auth:sanctum');
//     Route::patch('approval/{classId}', 'update')->middleware('auth:sanctum');
// });

// // RATES
// Route::get('/reports', [ReportController::class, 'index']);
// Route::get('/reports/{id}', [ReportController::class, 'show']);
// Route::post('/reports', [ReportController::class, 'store']);
// Route::patch('/reports/{id}', [ReportController::class, 'update']);
// Route::get('/reports/classes/{classId}', [ReportController::class, 'getTutorReportsForClass']);


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
        Route::match(['patch', 'post'], '{id}', 'update');
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
    // Route::get('/reports', 'index');
    // Route::get('/reports/{id}', 'show');
    // Route::post('/reports', 'store');
    // Route::patch('/reports/{id}', 'update');
    Route::get('/reports/classes/{classId}', 'getTutorReportsForClass');
});
Route::apiResource('reports', ReportController::class)->middleware('auth:sanctum');
