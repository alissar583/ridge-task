<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('notifications', NotificationController::class)->names('notification');
});

Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
    Route::post('', 'store')->middleware(['auth:sanctum']);
    Route::delete('/{notification}', 'destroy')->middleware(['auth:sanctum']);
    Route::get('', 'index');
});