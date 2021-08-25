<?php

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
Route::get('/yea', function (Request $request) {

    return $request->user()->name;
})->middleware('auth');
