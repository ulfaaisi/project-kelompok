<?php

namespace App\Services;

use App\Contracts\TMDb\TMDbServiceInterface;
use Illuminate\Support\Facades\Http;

class TMDbService implements TMDbServiceInterface
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
        $this->apiKey  = config('services.tmdb.api_key');
    }

    /**
     * Helper request ke TMDb
     */
    private function request(string $endpoint, array $params = []): array
    {
        $response = Http::get(
            $this->baseUrl . $endpoint,
            array_merge([
                'api_key'  => $this->apiKey,
                'language' => 'id-ID',
            ], $params)
        );

        if (!$response->successful()) {
            return [];
        }

        return $response->json();
    }

    /**
     * Ambil semua genre
     */
    public function getGenres(): array
    {
        $response = $this->request('/genre/movie/list');

        return $response['genres'] ?? [];
    }

    /**
     * Discover movie berdasarkan filter
     */
    public function discoverMovies(array $filters): array
    {
        $params = [
            'sort_by'       => 'popularity.desc',
            'include_adult' => false,
            'page'          => $filters['page'] ?? 1,
        ];

        if (!empty($filters['genre_id'])) {
            $params['with_genres'] = $filters['genre_id'];
        }

        if (!empty($filters['genre_id_2'])) {
            $params['with_genres'] =
                ($params['with_genres'] ?? '') .
                ',' .
                $filters['genre_id_2'];
        }

        if (!empty($filters['year'])) {
            $params['primary_release_year'] = $filters['year'];
        }

        if (!empty($filters['country'])) {
            $params['with_origin_country'] = strtoupper(
                $filters['country']
            );
        }

        return $this->request('/discover/movie', $params);
    }

    /**
     * Detail film
     */
    public function getMovieDetail(int $movieId): array
    {
        return $this->request(
            "/movie/{$movieId}",
            [
                'append_to_response' => 'credits,videos',
            ]
        );
    }

    /**
     * Gambar film
     */
    public function getMovieImages(int $movieId): array
    {
        $response = $this->request(
            "/movie/{$movieId}/images"
        );

        return $response['backdrops'] ?? [];
    }

    /**
     * Trailer YouTube
     */
    public function getMovieTrailer(int $movieId): ?string
    {
        $response = $this->request(
            "/movie/{$movieId}/videos"
        );

        foreach ($response['results'] ?? [] as $video) {

            if (
                ($video['site'] ?? '') === 'YouTube'
                && ($video['type'] ?? '') === 'Trailer'
            ) {
                return 'https://www.youtube.com/watch?v=' . $video['key'];
            }
        }

        return null;
    }

    /**
     * URL poster
     */
    public function getPosterUrl(?string $posterPath): ?string
    {
        if (!$posterPath) {
            return null;
        }

        return 'https://image.tmdb.org/t/p/w500' . $posterPath;
    }
}