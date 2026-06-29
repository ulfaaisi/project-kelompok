<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('/', function () {
    return "Laravel Berjalan";
});

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::post('/search', [HomeController::class, 'search'])
    ->name('search');

// Detail Movie
Route::get('/movie/{id}', [MovieController::class, 'show'])
    ->name('movie.show');

Route::get('/tmdb-test', function () {
    return app(
        \App\Contracts\TMDb\TMDbServiceInterface::class
    )->getGenres();
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.post');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');

    Route::post('/favorites', [FavoriteController::class, 'store'])
        ->name('favorites.store');

    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');
     
});
