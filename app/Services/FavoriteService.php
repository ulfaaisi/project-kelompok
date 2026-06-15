<?php

namespace App\Services;

use App\Contracts\FavoriteServiceInterface;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;

class FavoriteService implements FavoriteServiceInterface
{
    public function getUserFavorites(int $userId): Collection
    {
        return Favorite::where('user_id', $userId)->latest()->get();
    }

    public function addFavorite(int $userId, array $data): array
    {
        $exists = Favorite::where('user_id', $userId)
            ->where('movie_id', $data['movie_id'])
            ->exists();

        if ($exists) {
            return ['favorite' => null, 'already_exists' => true];
        }

        $favorite = Favorite::create([
            'user_id'      => $userId,
            'movie_id'     => $data['movie_id'],
            'movie_title'  => $data['movie_title'],
            'poster_path'  => $data['poster_path'] ?? null,
            'release_year' => $data['release_year'] ?? null,
            'rating'       => $data['rating'] ?? null,
        ]);

        return ['favorite' => $favorite, 'already_exists' => false];
    }

    public function removeFavorite(int $favoriteId, int $userId): bool
    {
        $favorite = Favorite::where('id', $favoriteId)
            ->where('user_id', $userId)
            ->first();

        if (!$favorite) {
            return false;
        }

        $favorite->delete();

        return true;
    }
}
