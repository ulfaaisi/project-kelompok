<?php

namespace App\DTO\Favorite;

class FavoriteMovieDTO
{
    public int $movie_id;
    public string $title;
    public string $poster_url;
    public int $release_year;
    public float $rating;

    public function __construct(
        int $movie_id,
        string $title,
        string $poster_url,
        int $release_year,
        float $rating
    ) {
        $this->movie_id = $movie_id;
        $this->title = $title;
        $this->poster_url = $poster_url;
        $this->release_year = $release_year;
        $this->rating = $rating;
    }
}