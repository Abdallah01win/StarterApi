<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('init_user', [AuthController::class, 'init_user']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('users', UserController::class)->except(['show'])->withTrashed(['index']);
});
