<?php

use App\Http\Controllers\Tasks\RecurringTaskController;
use App\Http\Controllers\Tasks\TaskController;
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
    Route::get('/tasks', [TaskController::class, 'getAll']);
    Route::get('/tasks/{id}', [TaskController::class, 'getById']);

    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    Route::post('/tasks/{id}/recurring', [RecurringTaskController::class, 'store']);
    Route::put('/tasks/{id}/recurring', [RecurringTaskController::class, 'update']);

    Route::post('/tasks/{task_id}/time-unit', [TimeUnitController::class, 'store']);
});
