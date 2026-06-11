<?php

namespace App\DTO\Movie;

class RecommendationFilterDTO
{
    public ?int $genre;
    public ?int $year;
    public ?float $min_rating;

    public function __construct(
        ?int $genre = null,
        ?int $year = null,
        ?float $min_rating = null
    ) {
        $this->genre = $genre;
        $this->year = $year;
        $this->min_rating = $min_rating;
    }
}