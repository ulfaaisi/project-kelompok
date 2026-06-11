<?php

namespace App\DTO\History;

class SearchHistoryDTO
{
    public int $id;
    public string $genre;
    public ?int $year;
    public ?float $min_rating;
    public string $searched_at;

    public function __construct(
        int $id,
        string $genre,
        ?int $year,
        ?float $min_rating,
        string $searched_at
    ) {
        $this->id = $id;
        $this->genre = $genre;
        $this->year = $year;
        $this->min_rating = $min_rating;
        $this->searched_at = $searched_at;
    }
}