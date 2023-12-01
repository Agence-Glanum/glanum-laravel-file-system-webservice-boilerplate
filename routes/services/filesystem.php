<?php

use App\Http\Controllers\FileSystem\V1\FolderController;
use App\Http\Controllers\FileSystem\V1\FolderFileController;
use Illuminate\Support\Facades\Route;

Route::prefix('filesystem')
    ->group(function () {
        Route::prefix('v1')->group(function () {
            Route::get('folders', [FolderController::class, 'index']);

            Route::get('folders/{folder}/files', [FolderFileController::class, 'index']);
            Route::get('folders/{folder}/files/{file}', [FolderFileController::class, 'show']);
            Route::put('folders/{folder}/files/{file}', [FolderFileController::class, 'update']);
            Route::delete('folders/{folder}/files/{file}', [FolderFileController::class, 'destroy']);
        });
    });
