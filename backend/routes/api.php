<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {

    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);

    // Me
    Route::get('me', [UserController::class, 'show']);
    Route::patch('me', [UserController::class, 'update']);

    // Follow
    Route::get('users/{user}/followers', [FollowController::class, 'followers']);
    Route::get('users/{user}/following', [FollowController::class, 'following']);
    Route::post('users/{target}/follow', [FollowController::class, 'follow']);
    Route::delete('users/{target}/follow', [FollowController::class, 'unfollow']);
    Route::get('users/suggestions', [FollowController::class, 'suggestions']);

    // Post
    Route::post('posts', [PostController::class, 'store']);
    Route::get('posts/{post}', [PostController::class, 'show']);
    Route::patch('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);

    Route::get('users/{user:username}/posts', [UserController::class, 'posts']);

    // Comments
    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::patch('comments/{comment}', [CommentController::class, 'update']);

    // Like
    Route::post('posts/{post}/likes', [LikeController::class, 'store']);
    Route::delete('posts/{post}/likes', [LikeController::class, 'destroy']);

    // Feed
    Route::get('feed', [PostController::class, 'feed']);

    // Stories
    Route::post('stories', [StoryController::class, 'store']);
    Route::delete('stories/{story}', [StoryController::class, 'destroy']);
    Route::get('stories/feed', [StoryController::class, 'feed']);

    // Users
    Route::get('users/{user:username}', [UserController::class, 'showProfile']);

});
