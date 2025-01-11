<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\TrashLocationController;
use App\Http\Controllers\UserAchievementController;
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
        Route::put('/update-profile', [LoginController::class, 'updateImage']);
        Route::post('/logout', [LoginController::class, 'logout']);

        Route::post('/scan-image', [TrashController::class, 'scanImage']);
        Route::get('/get-trash/{type}', [TrashController::class, 'getGroupData']);
        Route::get('/get-trash-category/{category_id}', [TrashController::class, 'getDataByUserAndCategory']);
        Route::get('/get-trash-user/', [TrashController::class, 'getAllDataByUserId']);
        Route::get('/trash-detail/{id}', [TrashController::class, 'getTrashDetail']);

        Route::get('/leaderboard', [LoginController::class, 'leaderboard']);

        Route::get('/get-all-locations', [TrashLocationController::class, 'getAllLocation']);
        Route::get('/get-all-locations/{category_id}', [TrashLocationController::class, 'getLocationByCategory']);
        Route::get('/location/{$id}', [TrashLocationController::class, 'getLocationById']);

        Route::get('/get-all-vouchers', [RewardController::class, 'getAllAvailVoucher']);
        Route::post('/voucher-redeem/{id}', [RewardController::class, 'redeemCode']);
        Route::get('/voucher/{id}', [RewardController::class, 'rewardDetail']);

        Route::get('/achievements', [UserAchievementController::class, 'index']);
        Route::get('/achievements/{id}', [UserAchievementController::class, 'show']);
        Route::post('/achievements/claim/{id}', [UserAchievementController::class, 'claim']);
    });
});



