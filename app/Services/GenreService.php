<?php

namespace App\Services;

use App\Contracts\GenreServiceInterface;
use App\Contracts\TMDb\TMDbServiceInterface;
use App\Models\Genre;
use Illuminate\Support\Facades\Cache;

class GenreService implements GenreServiceInterface
{
    private const CACHE_KEY = 'tmdb_genres';
    private const CACHE_TTL_SECONDS = 60 * 30;

    public function __construct(
        private readonly TMDbServiceInterface $tmdb
    ) {}

    public function getAllGenres(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            $tmdbGenres = $this->tmdb->getGenres();

            foreach ($tmdbGenres as $genre) {
                Genre::updateOrCreate(
                    ['tmdb_genre_id' => $genre['id']],
                    ['name' => $genre['name']]
                );
            }

            return Genre::orderBy('name')
                ->get(['id', 'tmdb_genre_id', 'name'])
                ->toArray();
        });
    }
}
