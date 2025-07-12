<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\PostController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('posts', PostController::class)->names('post');
});



Route::prefix('posts')->controller(PostController::class)->group(function () {
    Route::post('', 'store')->middleware(['auth:sanctum','role:editor']);
    Route::delete('/{post}', 'destroy')->middleware(['auth:sanctum','role:admin,editor']);
    Route::get('', 'index');
});
