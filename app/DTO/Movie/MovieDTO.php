<?php

namespace App\DTO\Movie;

class MovieDTO
{
    public int $id;
    public string $title;
    public string $overview;
    public string $poster_url;
    public ?string $backdrop_url;
    public int $release_year;
    public float $rating;
    public ?string $trailer_url;

    public function __construct(
        int $id,
        string $title,
        string $overview,
        string $poster_url,
        ?string $backdrop_url,
        int $release_year,
        float $rating,
        ?string $trailer_url
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->overview = $overview;
        $this->poster_url = $poster_url;
        $this->backdrop_url = $backdrop_url;
        $this->release_year = $release_year;
        $this->rating = $rating;
        $this->trailer_url = $trailer_url;
    }
}