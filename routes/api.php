<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriptionController;
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

// Публичный маршрут получение изображения
Route::get('/images/{fileName}', [ImageController::class, 'getImage']);

// Защищенные маршруты
Route::middleware(['auth:sanctum'])->group(function() {
    // Маршрут для выхода
    Route::post('/logout', [AuthController::class, 'logout']);

    // Маршруты для пользователей
    Route::patch('/users/about', [UserController::class, 'updateAbout']);
    Route::patch('/users/avatar', [UserController::class, 'updateAvatar']);
    Route::patch('/users/head-avatar', [UserController::class, 'updateHeaderAvatar']);
    Route::patch('/users/full-name', [UserController::class, 'updateFullName']);
    Route::patch('/users/birthday', [UserController::class, 'updateBirthday']);
    Route::delete('/users-delete', [UserController::class, 'deleteUser']);

    // Маршруты для подписок
    Route::get('/subscriptions', [SubscriptionController::class, 'getSubscriptions']);
    Route::get('/subscribers', [SubscriptionController::class, 'getSubscribers']);
    Route::post('/users/{userId}/toggle-subscription', [SubscriptionController::class, 'toggleSubscribe']);

    // Маршруты для постов
    Route::post('/posts', [PostController::class, 'createPost']);
    Route::put('/posts/{postId}', [PostController::class, 'updatePost']);
    Route::delete('/posts/{postId}', [PostController::class, 'deletePost']);

    // Маршруты для комментариев
    Route::post('/posts/{postId}/comments', [CommentController::class, 'createComment']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'deleteComment']);

    // Маршруты лайков
    Route::post('/posts/{postId}/like', [LikeController::class, 'toggleLike']);
    Route::get('/user/likes-posts', [LikeController::class, 'getLikesPosts']);
});

// Маршруты для администраторов
Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::put('/users/{userId}/role', [RoleController::class, 'updateRole']);
});
