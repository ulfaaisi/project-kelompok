<?php

declare(strict_types=1);

namespace App\Contracts\TMDb;

/**
 * Stub contract khusus unit test.
 * Hapus file ini setelah contract TMDb asli tersedia di app/Contracts/TMDb.
 */
interface TMDbServiceInterface
{
    public function getGenres(): array;

    public function discoverMovies(array $filters): array;

    public function getMovieTrailer(int $movieId): ?string;

    public function getMovieDetail(int $movieId): array;

    public function getMovieImages(int $movieId): array;

    public function getPosterUrl(?string $posterPath): ?string;
}
