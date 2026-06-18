<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\TMDb\TMDbServiceInterface;
use App\Models\Genre;
use App\Services\GenreService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class GenreServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_get_all_genres_returns_cached_data_without_calling_tmdb(): void
    {
        $tmdb = Mockery::mock(TMDbServiceInterface::class);
        $cachedGenres = [
            ['id' => 1, 'tmdb_genre_id' => 28, 'name' => 'Action'],
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->with('tmdb_genres', 1800, Mockery::type('Closure'))
            ->andReturn($cachedGenres);
        $tmdb->shouldReceive('getGenres')->never();

        $result = (new GenreService($tmdb))->getAllGenres();

        self::assertSame($cachedGenres, $result);
    }

    public function test_get_all_genres_synchronizes_tmdb_data_when_cache_is_empty(): void
    {
        $genreModel = Mockery::mock('alias:'.Genre::class);
        $tmdb = Mockery::mock(TMDbServiceInterface::class);
        $query = Mockery::mock();

        $tmdbGenres = [
            ['id' => 35, 'name' => 'Comedy'],
            ['id' => 28, 'name' => 'Action'],
        ];
        $databaseGenres = collect([
            ['id' => 1, 'tmdb_genre_id' => 28, 'name' => 'Action'],
            ['id' => 2, 'tmdb_genre_id' => 35, 'name' => 'Comedy'],
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->with('tmdb_genres', 1800, Mockery::type('Closure'))
            ->andReturnUsing(static fn (string $key, int $ttl, \Closure $callback) => $callback());

        $tmdb->shouldReceive('getGenres')->once()->andReturn($tmdbGenres);

        $genreModel->shouldReceive('updateOrCreate')
            ->once()
            ->with(['tmdb_genre_id' => 35], ['name' => 'Comedy']);
        $genreModel->shouldReceive('updateOrCreate')
            ->once()
            ->with(['tmdb_genre_id' => 28], ['name' => 'Action']);
        $genreModel->shouldReceive('orderBy')
            ->once()
            ->with('name')
            ->andReturn($query);
        $query->shouldReceive('get')
            ->once()
            ->with(['id', 'tmdb_genre_id', 'name'])
            ->andReturn($databaseGenres);

        $result = (new GenreService($tmdb))->getAllGenres();

        self::assertSame($databaseGenres->toArray(), $result);
    }
}
