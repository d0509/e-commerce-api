<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
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



Route::middleware('guest')->group(function () {
    Route::post('sign-up', [AuthController::class, 'signUp'])->name('SignUp');
    Route::post('login', [AuthController::class, 'login'])->name('Login');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('Logout');
    Route::apiResource('user/profile', UserController::class)->only('index','update');
    Route::apiResource('product', ProductController::class);
    Route::apiResource('media',MediaController::class)->only('store','destroy');
});
