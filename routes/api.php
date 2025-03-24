<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->withoutMiddleware('throttle:api')
    ->middleware('throttle:login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('init_user', [AuthController::class, 'init_user']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class)
        ->except(['show'])
        ->withTrashed(['index']);

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/mark_as_read', [NotificationController::class, 'markAsRead']);
    });
});
