<?php

namespace App\Contracts\TMDb;

interface TMDbServiceInterface
{
    public function getGenres(): array;

    public function discoverMovies(array $filters): array;

    public function getMovieTrailer(int $movieId): ?string;

    public function getMovieDetail(int $movieId): array;

    public function getMovieImages(int $movieId): array;

    public function getPosterUrl(?string $posterPath): ?string;
}
