<?php

namespace App\Providers;

use App\Contracts\AuthServiceInterface;
use App\Contracts\FavoriteServiceInterface;
use App\Contracts\GenreServiceInterface;
use App\Contracts\HistoryServiceInterface;
use App\Contracts\MovieServiceInterface;
use App\Contracts\TMDb\TMDbServiceInterface;
use App\Services\AuthService;
use App\Services\FavoriteService;
use App\Services\GenreService;
use App\Services\HistoryService;
use App\Services\MovieService;
use App\Services\TMDbService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class
        );

        $this->app->bind(
            GenreServiceInterface::class,
            GenreService::class
        );

        $this->app->bind(
            MovieServiceInterface::class,
            MovieService::class
        );

        $this->app->bind(
            FavoriteServiceInterface::class,
            FavoriteService::class
        );

        $this->app->bind(
            HistoryServiceInterface::class,
            HistoryService::class
        );

        $this->app->bind(
            TMDbServiceInterface::class,
            TMDbService::class
        );
    }

    public function boot(): void
    {
        //
    }
}