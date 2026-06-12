<?php

namespace App\Services;

use App\Contracts\HistoryServiceInterface;
use App\Models\SearchHistory;

class HistoryService implements HistoryServiceInterface
{
    private const DEFAULT_LIMIT = 50;

    public function getUserHistory(int $userId, int $limit = self::DEFAULT_LIMIT): array
    {
        return SearchHistory::with('genre')
            ->where('user_id', $userId)
            ->latest('searched_at')
            ->take($limit)
            ->get()
            ->map(fn (SearchHistory $history) => [
                'id'   => $history->id,
                'genre' => $history->genre
                    ? ['id' => $history->genre->tmdb_genre_id, 'name' => $history->genre->name]
                    : null,
                'year'        => $history->year,
                'min_rating'  => $history->rating,
                'searched_at' => $history->searched_at->toIso8601String(),
            ])
            ->values()
            ->all();
    }
}
