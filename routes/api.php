<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::group(['middleware'=>['auth:sanctum', 'throttle:api']], function () {
    Route::get('init_user', [AuthController::class, 'init_user']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware([ 'auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('users', UserController::class)->except(['show']);
});
