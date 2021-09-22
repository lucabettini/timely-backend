<?php

use App\Http\Controllers\Tasks\EditTaskController;
use App\Http\Controllers\Tasks\GetTaskController;
use App\Http\Controllers\Tasks\RecurringTaskController;
use App\Http\Controllers\Tasks\TimeUnitController;
use App\Http\Controllers\Users\AccountController;
use App\Http\Controllers\Users\LoginController;
use App\Http\Controllers\Users\LogoutController;
use App\Http\Controllers\Users\PasswordController;
use App\Http\Controllers\Users\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/


// PUBLIC ROUTES
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);
Route::post('/forgotPassword', [PasswordController::class, 'forgot']);
Route::post('/resetPassword', [PasswordController::class, 'reset'])->name('password.reset');


// PROTECTED ROUTES 
Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [GetTaskController::class, 'getAll']);
    Route::get('/tasks/open', [GetTaskController::class, 'getOpen']);
    Route::get('/tasks/overdue', [GetTaskController::class, 'getOverdue']);
    Route::get('/tasks/today', [GetTaskController::class, 'getToday']);
    Route::get('/tasks/tomorrow', [GetTaskController::class, 'getTomorrow']);
    Route::get('/tasks/week', [GetTaskController::class, 'getWeek']);
    Route::get('/tasks/{id}', [GetTaskController::class, 'getById']);

    Route::post('/tasks', [EditTaskController::class, 'store']);
    Route::patch('/tasks/{id}/complete', [EditTaskController::class, 'complete']);
    Route::patch('/tasks/{id}/incomplete', [EditTaskController::class, 'makeIncomplete']);
    Route::put('/tasks/{id}', [EditTaskController::class, 'update']);
    Route::delete('/tasks/{id}', [EditTaskController::class, 'destroy']);


    Route::get('/areas', [GetTaskController::class, 'getAreas']);
    Route::get('/area/{area}', [GetTaskController::class, 'getArea']);
    Route::get('/area/{area}/bucket/{bucket}', [GetTaskController::class, 'getByBucket']);
    Route::patch('/area', [EditTaskController::class, 'editAreaName']);
    Route::patch('/bucket', [EditTaskController::class, 'editBucketName']);
    Route::delete('/bucket', [EditTaskController::class, 'deleteByBucket']);


    Route::post('/tasks/{id}/recurring', [RecurringTaskController::class, 'store']);
    Route::put('/tasks/{id}/recurring', [RecurringTaskController::class, 'update']);
    Route::delete('/tasks/{id}/recurring', [RecurringTaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/recurring/complete', [RecurringTaskController::class, 'complete']);

    Route::get('/time_unit', [TimeUnitController::class, 'getStarted']);
    Route::post('/tasks/{task_id}/time_unit', [TimeUnitController::class, 'store']);
    Route::put('/time_unit/{id}', [TimeUnitController::class, 'update']);
    Route::delete('/time_unit/{id}', [TimeUnitController::class, 'destroy']);

    Route::post('/logout', [LogoutController::class, 'store']);
    Route::post('/changePassword', [PasswordController::class, 'change']);
    Route::patch('/editAccount', [AccountController::class, 'update']);
    Route::delete('/deleteAccount', [AccountController::class, 'destroy']);
});
