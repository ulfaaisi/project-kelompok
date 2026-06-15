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
        $detail = $this->tmdb->getMovieDetail($movieId);
        $images = $this->tmdb->getMovieImages($movieId);

        return [
            'id'           => $detail['id'],
            'title'        => $detail['title'],
            'overview'     => $detail['overview'],
            'poster_url'   => $this->tmdb->getPosterUrl($detail['poster_path'] ?? null),
            'backdrop_url' => isset($detail['backdrop_path'])
                ? self::IMAGE_BASE_URL_W1280 . $detail['backdrop_path']
                : null,
            'release_date' => $detail['release_date'] ?? null,
            'release_year' => isset($detail['release_date'])
                ? substr($detail['release_date'], 0, 4)
                : null,
            'rating'       => round($detail['vote_average'] ?? 0, 1),
            'vote_count'   => $detail['vote_count'] ?? 0,
            'genres'       => $this->formatGenres($detail['genres'] ?? []),
            'cast'         => $this->formatCast($detail['credits']['cast'] ?? []),
            'gallery'      => $this->formatGallery($images),
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

    private function formatRecommendation(array $movie, ?string $trailerUrl): array
    {
        return [
            'id'           => $movie['id'],
            'title'        => $movie['title'],
            'overview'     => $movie['overview'],
            'poster_url'   => $this->tmdb->getPosterUrl($movie['poster_path'] ?? null),
            'backdrop_url' => $movie['backdrop_path']
                ? self::IMAGE_BASE_URL_W1280 . $movie['backdrop_path']
                : null,
            'release_date' => $movie['release_date'] ?? null,
            'release_year' => isset($movie['release_date'])
                ? substr($movie['release_date'], 0, 4)
                : null,
            'rating'       => round($movie['vote_average'] ?? 0, 1),
            'vote_count'   => $movie['vote_count'] ?? 0,
            'genre_ids'    => $movie['genre_ids'] ?? [],
            'trailer_url'  => $trailerUrl,
            'trailer_available' => !is_null($trailerUrl),
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
