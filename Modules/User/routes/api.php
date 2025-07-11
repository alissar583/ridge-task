<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\PostController;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class)->names('user');
});


Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('', 'index');
    Route::get('/{user}', 'show');
});

Route::prefix('posts')->controller(PostController::class)->group(function () {
    Route::post('', 'store')->middleware('auth:sanctum');
    Route::delete('/{post}', 'destroy');
});
