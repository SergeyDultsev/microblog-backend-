<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Маршруты для регистрации и входа
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Публичные маршруты
Route::get('/users/{userId}', [UserController::class, 'getUser']);
Route::get('/users/search', [UserController::class, 'searchUser']);

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
});
