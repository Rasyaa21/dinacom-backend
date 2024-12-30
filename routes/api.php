<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TrashController;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::prefix('/v1')->group(function () {

    Route::prefix('/admin')->group(function () {
        Route::get('/all-data', [LoginController::class, 'getAllData']);
        Route::get('/user/{uuid}', [LoginController::class, 'findUser']);
    });

    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/update', [LoginController::class, 'updateProfile']);
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::post('/scan-image', [TrashController::class, 'scanImage']);
        Route::get('/get-trash/{type}', [TrashController::class, 'getGroupData']);
        Route::get('/leaderboard', [LoginController::class, 'leaderboard']);
    });
});



