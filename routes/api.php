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
Route::get('parents/getAll', [Parent1Controller::class, 'getAll']);
Route::resource('parents', Parent1Controller::class);
Route::match(['patch', 'post'], '/tutors/{id}', [TutorController::class, 'update']);
Route::resource('tutors', TutorController::class);
Route::get('classes/get12Classes', [Class1Controller::class, 'get12Classes']);
Route::get('classes/getAllNewClasses', [Class1Controller::class, 'getAllNewClasses']);
Route::resource('classes', Class1Controller::class);
Route::post('tutors/createAccount', [TutorController::class, 'createAccount']);
Route::get('wards/getAllBelongToDistrict/{id}', [WardController::class, 'getAllBelongToDistrict']);

Route::get('/districts', [DistrictController::class, 'index'])->name('districts.index');
Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/levels', [LevelController::class, 'index'])->name('levels.index');
Route::get('/tuitions', [TuitionController::class, 'index'])->name('tuitions.index');

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('account', 'profile')->middleware('auth:sanctum');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});
