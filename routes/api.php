<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Маршруты для регистрации и входа
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Защищенные маршруты
Route::middleware(['auth:sanctum'])->group(function () {
    // Маршрут для выхода
    Route::post('/logout', [AuthController::class, 'logout']);
});
