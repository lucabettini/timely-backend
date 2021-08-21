<?php

use App\Http\Controllers\Auth\RegisterController;
use Firebase\JWT\JWT;
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

// PROTECTED ROUTES 
Route::get('/yea', function () {
    return 'This route is protected';
})->middleware('auth');
