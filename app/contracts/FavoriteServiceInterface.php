<?php

namespace App\Contracts;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;

interface FavoriteServiceInterface
{
    public function getUserFavorites(int $userId): Collection;

    public function addFavorite(int $userId, array $data): array;

    public function removeFavorite(int $favoriteId, int $userId): bool;
}
