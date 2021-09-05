<?php

use App\Http\Controllers\Tasks\EditTaskController;
use App\Http\Controllers\Tasks\GetTaskController;
use App\Http\Controllers\Tasks\RecurringTaskController;
use App\Http\Controllers\Tasks\TimeUnitController;
use App\Http\Controllers\Users\LoginController;
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

// PROTECTED ROUTES 
Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [GetTaskController::class, 'getAll']);
    Route::get('/tasks/open', [GetTaskController::class, 'getOpen']);
    Route::get('/tasks/overdue', [GetTaskController::class, 'getOverdue']);
    Route::get('/tasks/week', [GetTaskController::class, 'getWeek']);
    Route::get('tasks/archive/{area}', [GetTaskController::class, 'getInactiveByArea']);
    Route::get('tasks/active/{area}', [GetTaskController::class, 'getActiveByArea']);
    Route::get('/tasks/{id}', [GetTaskController::class, 'getById']);
    Route::get('/areas', [GetTaskController::class, 'getAreas']);

    Route::post('/tasks', [EditTaskController::class, 'store']);
    Route::patch('/tasks/{id}/complete', [EditTaskController::class, 'complete']);
    Route::patch('/tasks/{id}/incomplete', [EditTaskController::class, 'makeIncomplete']);
    Route::put('/tasks/{id}', [EditTaskController::class, 'update']);
    Route::delete('/tasks/{id}', [EditTaskController::class, 'destroy']);
    Route::patch('/bucket', [EditTaskController::class, 'editBucketName']);
    Route::delete('/bucket', [EditTaskController::class, 'deleteByBucket']);


    Route::post('/tasks/{id}/recurring', [RecurringTaskController::class, 'store']);
    Route::put('/tasks/{id}/recurring', [RecurringTaskController::class, 'update']);
    Route::delete('/tasks/{id}/recurring', [RecurringTaskController::class, 'destroy']);
    Route::post('/tasks/{id}/recurring/complete', [RecurringTaskController::class, 'complete']);

    Route::post('/tasks/{task_id}/time-unit', [TimeUnitController::class, 'store']);
    Route::put('/time_units/{id}', [TimeUnitController::class, 'update']);
    Route::delete('/time_units/{id}', [TimeUnitController::class, 'destroy']);
});
