<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\PlatformsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [UserController::class, 'getUser']);
    Route::put('/user/change-password', [UserController::class, 'changePassword']);

    Route::post('/user/preferences', [UserPreferenceController::class, 'updatePreferences']);
    Route::get('/user/preferences', [UserPreferenceController::class, 'getUserPreferences']);

    Route::get('/articles', [ArticlesController::class, 'index']);
    Route::get('/platforms', [PlatformsController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/sources', [SourceController::class, 'index']);
    Route::get('/authors', [AuthorController::class, 'index']);
});
