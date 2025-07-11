<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class)->names('user');
});


Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('', 'index');
    Route::get('/{user}', 'show');
});

Route::post('/mock-login', [AuthController::class, 'mockLogin']);

