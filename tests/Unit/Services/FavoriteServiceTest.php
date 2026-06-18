<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Favorite;
use App\Services\FavoriteService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class FavoriteServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_get_user_favorites_filters_by_user_and_orders_latest(): void
    {
        $favoriteModel = Mockery::mock('alias:'.Favorite::class);
        $query = Mockery::mock();
        $favorites = new Collection([(object) ['movie_id' => 101]]);

        $favoriteModel->shouldReceive('where')
            ->once()
            ->with('user_id', 7)
            ->andReturn($query);
        $query->shouldReceive('latest')->once()->andReturnSelf();
        $query->shouldReceive('get')->once()->andReturn($favorites);

        $result = (new FavoriteService())->getUserFavorites(7);

        self::assertSame($favorites, $result);
    }

    public function test_add_favorite_rejects_duplicate_movie(): void
    {
        $favoriteModel = Mockery::mock('alias:'.Favorite::class);
        $query = Mockery::mock();

        $favoriteModel->shouldReceive('where')
            ->once()
            ->with('user_id', 7)
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('movie_id', 101)
            ->andReturnSelf();
        $query->shouldReceive('exists')->once()->andReturnTrue();
        $favoriteModel->shouldReceive('create')->never();

        $result = (new FavoriteService())->addFavorite(7, [
            'movie_id' => 101,
            'movie_title' => 'Interstellar',
        ]);

        self::assertTrue($result['already_exists']);
        self::assertNull($result['favorite']);
    }

    public function test_add_favorite_creates_new_record(): void
    {
        // Gunakan satu alias mock untuk static Favorite::create() sekaligus
        // sebagai object favorite hasil create agar class mock tidak bentrok.
        $favorite = Mockery::mock('alias:'.Favorite::class);
        $query = Mockery::mock();

        $favorite->shouldReceive('where')
            ->once()
            ->with('user_id', 7)
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('movie_id', 101)
            ->andReturnSelf();
        $query->shouldReceive('exists')->once()->andReturnFalse();

        $favorite->shouldReceive('create')
            ->once()
            ->with([
                'user_id' => 7,
                'movie_id' => 101,
                'movie_title' => 'Interstellar',
                'poster_path' => '/poster.jpg',
                'release_year' => '2014',
                'rating' => 8.7,
            ])
            ->andReturn($favorite);

        $result = (new FavoriteService())->addFavorite(7, [
            'movie_id' => 101,
            'movie_title' => 'Interstellar',
            'poster_path' => '/poster.jpg',
            'release_year' => '2014',
            'rating' => 8.7,
        ]);

        self::assertFalse($result['already_exists']);
        self::assertSame($favorite, $result['favorite']);
    }

    public function test_remove_favorite_returns_false_when_record_is_not_found(): void
    {
        $favoriteModel = Mockery::mock('alias:'.Favorite::class);
        $query = Mockery::mock();

        $favoriteModel->shouldReceive('where')
            ->once()
            ->with('id', 99)
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('user_id', 7)
            ->andReturnSelf();
        $query->shouldReceive('first')->once()->andReturnNull();

        self::assertFalse((new FavoriteService())->removeFavorite(99, 7));
    }

    public function test_remove_favorite_deletes_record_owned_by_user(): void
    {
        // Alias mock yang sama digunakan sebagai hasil query dan record yang dihapus.
        $favorite = Mockery::mock('alias:'.Favorite::class);
        $query = Mockery::mock();

        $favorite->shouldReceive('where')
            ->once()
            ->with('id', 99)
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('user_id', 7)
            ->andReturnSelf();
        $query->shouldReceive('first')->once()->andReturn($favorite);
        $favorite->shouldReceive('delete')->once()->andReturnTrue();

        self::assertTrue((new FavoriteService())->removeFavorite(99, 7));
    }
}
