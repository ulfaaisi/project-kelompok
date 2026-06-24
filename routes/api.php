<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HistoryController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/genres', [GenreController::class, 'index']);

Route::get('/movies/recommendation', [MovieController::class, 'recommendation']);
Route::get('/movies/{movieId}', [MovieController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{movieId}', [FavoriteController::class, 'destroy']);

    Route::get('/history', [HistoryController::class, 'index']);
});


Route::middleware('auth:sanctum')->get('/debug-cookie', function (Request $request) {
    return response()->json([
        'cookies' => $request->cookies->all(),
        'session_id' => session()->getId(),
        'authenticated' => auth()->check(),
    ]);
});
