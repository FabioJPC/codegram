<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);

    Route::get('me', [UserController::class, 'show']);
    Route::patch('me', [UserController::class, 'update']);

    Route::get('users/{user}/followers', [FollowController::class, 'followers']);
    Route::get('users/{user}/following', [FollowController::class, 'following']);
    Route::post('users/{target}/follow', [FollowController::class, 'follow']);
    Route::delete('users/{target}/unfollow', [FollowController::class, 'unfollow']);

});
