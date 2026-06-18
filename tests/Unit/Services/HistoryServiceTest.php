<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\SearchHistory;
use App\Services\HistoryService;
use Illuminate\Support\Carbon;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class HistoryServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_get_user_history_applies_limit_and_formats_result(): void
    {
        $historyModel = Mockery::mock('alias:'.SearchHistory::class);
        $query = Mockery::mock();

        $genre = (object) [
            'tmdb_genre_id' => 28,
            'name' => 'Action',
        ];

        $history = new SearchHistory();
        $history->id = 20;
        $history->genre = $genre;
        $history->year = 2024;
        $history->rating = 7.5;
        $history->searched_at = Carbon::parse('2026-06-18 08:00:00', 'Asia/Jakarta');

        $historyModel->shouldReceive('with')
            ->once()
            ->with('genre')
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('user_id', 7)
            ->andReturnSelf();
        $query->shouldReceive('latest')
            ->once()
            ->with('searched_at')
            ->andReturnSelf();
        $query->shouldReceive('take')
            ->once()
            ->with(10)
            ->andReturnSelf();
        $query->shouldReceive('get')
            ->once()
            ->andReturn(collect([$history]));

        $result = (new HistoryService())->getUserHistory(7, 10);

        self::assertSame([
            [
                'id' => 20,
                'genre' => ['id' => 28, 'name' => 'Action'],
                'year' => 2024,
                'min_rating' => 7.5,
                'searched_at' => $history->searched_at->toIso8601String(),
            ],
        ], $result);
    }
}
