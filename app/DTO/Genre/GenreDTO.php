<?php

namespace App\DTO\Genre;

class GenreDTO
{
    public int $id;
    public int $tmdb_genre_id;
    public string $name;

    public function __construct(
        int $id,
        int $tmdb_genre_id,
        string $name
    ) {
        $this->id = $id;
        $this->tmdb_genre_id = $tmdb_genre_id;
        $this->name = $name;
    }
}