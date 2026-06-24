<?php

namespace App\Services;

use App\Contracts\MovieServiceInterface;
use App\Contracts\TMDb\TMDbServiceInterface;
use App\Models\Genre;
use App\Models\SearchHistory;

class MovieService implements MovieServiceInterface
{
    private const MAX_PAGES_TO_SAMPLE = 5;
    private const MAX_CAST = 12;
    private const MAX_GALLERY = 12;

    private const PROFILE_BASE_URL_W185 = 'https://image.tmdb.org/t/p/w185';
    private const IMAGE_BASE_URL_W780   = 'https://image.tmdb.org/t/p/w780';
    private const IMAGE_BASE_URL_W1280  = 'https://image.tmdb.org/t/p/w1280';

    public function __construct(
        private readonly TMDbServiceInterface $tmdb
    ) {}

    public function getRecommendation(array $filters, int $userId): ?array
    {
        $initialResult = $this->tmdb->discoverMovies($filters);

        if (empty($initialResult['results'])) {
            return null;
        }

        $totalPages = min($initialResult['total_pages'], self::MAX_PAGES_TO_SAMPLE);
        $allMovies  = collect($initialResult['results']);

        if ($totalPages > 1) {
            $randomPage  = rand(2, $totalPages);
            $extraResult = $this->tmdb->discoverMovies(array_merge($filters, ['page' => $randomPage]));
            $allMovies   = $allMovies->merge($extraResult['results'] ?? []);
        }

        $selectedMovie = $allMovies->random();
        $trailerUrl    = $this->tmdb->getMovieTrailer($selectedMovie['id']);

        $this->saveSearchHistory($filters, $userId);

        return $this->formatRecommendation($selectedMovie, $trailerUrl);
    }

    public function getDetail(int $movieId): array
{
    $movie = $this->tmdb->getMovieDetail($movieId);

    $images = $this->tmdb->getMovieImages($movieId);

    $trailerUrl =
        $this->tmdb->getMovieTrailer($movieId);

    return [

        'id' => $movie['id'],

        'title' =>
            $movie['title'] ?? '',

        'overview' =>
            $movie['overview']
                ?: 'Sinopsis belum tersedia.',

        'poster_url' =>
            $this->tmdb->getPosterUrl(
                $movie['poster_path'] ?? null
            ),

        'backdrop_url' =>
            isset($movie['backdrop_path'])
                ? 'https://image.tmdb.org/t/p/w1280'
                    .$movie['backdrop_path']
                : null,

        'release_date' =>
            $movie['release_date'] ?? null,

        'release_year' =>
            !empty($movie['release_date'])
                ? substr(
                    $movie['release_date'],
                    0,
                    4
                )
                : null,

        'rating' =>
            round(
                $movie['vote_average'] ?? 0,
                1
            ),

        'vote_count' =>
            $movie['vote_count'] ?? 0,

        'genres' =>
            $movie['genres'] ?? [],

        'cast' =>
            collect(
                $movie['credits']['cast'] ?? []
            )
            ->take(12)
            ->map(fn ($cast) => [
                'id' => $cast['id'],
                'name' => $cast['name'],
                'character' =>
                    $cast['character'],
                'profile_url' =>
                    isset(
                        $cast['profile_path']
                    )
                        ? 'https://image.tmdb.org/t/p/w185'
                            .$cast['profile_path']
                        : null,
            ])
            ->values(),

        'gallery' =>
            collect($images)
                ->take(12)
                ->map(fn ($image) => [
                    'image_url' =>
                        'https://image.tmdb.org/t/p/w780'
                        .$image['file_path']
                ])
                ->values(),

        'trailer_url' =>
            $trailerUrl,

        'trailer_available' =>
            !empty($trailerUrl),
    ];
}

    private function saveSearchHistory(array $filters, int $userId): void
    {
        $genreId = null;
        if (!empty($filters['genre_id'])) {
            $genre   = Genre::where('tmdb_genre_id', $filters['genre_id'])->first();
            $genreId = $genre?->id;
        }

        SearchHistory::create([
            'user_id'     => $userId,
            'genre_id'    => $genreId,
            'year'        => $filters['year'] ?? null,
            'rating'      => $filters['min_rating'] ?? null,
            'searched_at' => now(),
        ]);
    }

    private function formatRecommendation(
    array $movie,
    ?string $trailerUrl
): array {

    $overview =
        $movie['overview'] ?? '';

    if (empty(trim($overview))) {
        $overview =
            'Sinopsis belum tersedia untuk film ini.';
    }

    return [
        'id' => $movie['id'],

        'title' =>
            $movie['title'] ?? '',

        'overview' =>
            $overview,

        'poster_url' =>
            isset($movie['poster_path'])
                ? 'https://image.tmdb.org/t/p/w500'.$movie['poster_path']
                : null,

        'backdrop_url' =>
            isset($movie['backdrop_path'])
                ? 'https://image.tmdb.org/t/p/w1280'.$movie['backdrop_path']
                : null,

        'release_date' =>
            $movie['release_date'] ?? null,

        'release_year' =>
            !empty($movie['release_date'])
                ? substr($movie['release_date'],0,4)
                : null,

        'rating' =>
            round(
                $movie['vote_average'] ?? 0,
                1
            ),

        'vote_count' =>
            $movie['vote_count'] ?? 0,

        'genre_ids' =>
            $movie['genre_ids'] ?? [],

        'trailer_url' =>
            $trailerUrl,

        'trailer_available' =>
            !empty($trailerUrl),
    ];
}
    private function formatGenres(array $genres): array
    {
        return collect($genres)
            ->map(fn ($g) => ['id' => $g['id'], 'name' => $g['name']])
            ->values()
            ->all();
    }

    private function formatCast(array $cast): array
    {
        return collect($cast)
            ->take(self::MAX_CAST)
            ->map(fn ($person) => [
                'id'          => $person['id'],
                'name'        => $person['name'],
                'character'   => $person['character'] ?? null,
                'profile_url' => !empty($person['profile_path'])
                    ? self::PROFILE_BASE_URL_W185 . $person['profile_path']
                    : null,
            ])
            ->values()
            ->all();
    }

    private function formatGallery(array $images): array
    {
        return collect($images)
            ->take(self::MAX_GALLERY)
            ->map(fn ($img) => [
                'image_url' => self::IMAGE_BASE_URL_W780 . $img['file_path'],
                'width'     => $img['width'] ?? null,
                'height'    => $img['height'] ?? null,
            ])
            ->values()
            ->all();
    }
}
