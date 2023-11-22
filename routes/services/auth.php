<?php

use App\Http\Controllers\Auth\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->group(function () {
        Route::prefix('v1')->group(function () {
            Route::apiResource('users', UserController::class);
        });
    });
