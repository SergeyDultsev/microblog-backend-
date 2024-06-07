<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Маршруты для регистрации и входа
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Публичные маршруты для пользователей
Route::get('/users/{userId}', [UserController::class, 'getUser']);
Route::get('/search/users/', [UserController::class, 'searchUser']);

// Публичные маршруты для постов
Route::get('/feed', [PostController::class, 'feed']);
Route::get('/user/{userId}/posts', [PostController::class, 'getUserPosts']);
Route::get('/posts/{postId}', [PostController::class, 'getPost']);

// Публичные маршруты для комментариев
Route::get('/posts/{postId}/comments', [CommentController::class, 'getComments']);
Route::get('/comments/{commentId}', [CommentController::class, 'getComment']);

// Защищенные маршруты
Route::middleware(['auth:sanctum'])->group(function() {
    // Маршрут для выхода
    Route::post('/logout', [AuthController::class, 'logout']);

    // Маршруты для пользователей
    Route::put('/users/{userId}/role', [UserController::class, 'updateRole'])->middleware('admin');
    Route::patch('/users/{userId}/about', [UserController::class, 'updateUserAbout']);
    Route::patch('/users/{userId}/avatar', [UserController::class, 'updateUserAvatar']);
    Route::patch('/users/{userId}/head-avatar', [UserController::class, 'updateUserHeadAvatar']);
    Route::delete('/users/{userId}', [UserController::class, 'deleteUser']);

    // Маршруты для постов
    Route::post('/posts', [PostController::class, 'createPost']);
    Route::put('/posts/{postId}', [PostController::class, 'updatePost']);
    Route::delete('/posts/{postId}', [PostController::class, 'deletePost']);

    // Маршруты для комментариев
    Route::post('/posts/{postId}/comments', [CommentController::class, 'createComment']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'deleteComment']);

    // Маршруты лайков
    Route::post('/posts/{postId}/like', [LikeController::class, 'toggleLike']);
    Route::get('/user/{userId}/likes-posts', [LikeController::class, 'getLikesPosts']);
});
