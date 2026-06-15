<?php

namespace App\Providers;

use App\Contracts\AuthServiceInterface;
use App\Contracts\FavoriteServiceInterface;
use App\Contracts\GenreServiceInterface;
use App\Contracts\HistoryServiceInterface;
use App\Contracts\MovieServiceInterface;
use App\Services\AuthService;
use App\Services\FavoriteService;
use App\Services\GenreService;
use App\Services\HistoryService;
use App\Services\MovieService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Binding semua 5 contracts ke implementasinya.
     *
     * Cara kerja:
     * - Controller type-hint interface di constructor
     * - Laravel container inject implementasi yang terdaftar di sini
     * - Jika implementasi berubah, cukup ganti di sini, controller tidak perlu diubah
     */
    public function register(): void
    {
        // Auth — login, register, logout
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        // Genre — ambil & sinkron genre dari TMDb
        $this->app->bind(GenreServiceInterface::class, GenreService::class);

        // Movie — rekomendasi, detail, format data
        $this->app->bind(MovieServiceInterface::class, MovieService::class);

        // Favorite — simpan, hapus, list favorit
        $this->app->bind(FavoriteServiceInterface::class, FavoriteService::class);

        // History — simpan & tampilkan riwayat pencarian
        $this->app->bind(HistoryServiceInterface::class, HistoryService::class);
    }

    public function boot(): void
    {
        //
    }
}
