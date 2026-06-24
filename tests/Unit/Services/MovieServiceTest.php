<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\TMDb\TMDbServiceInterface;
use App\Models\Genre;
use App\Models\SearchHistory;
use App\Services\MovieService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class MovieServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_get_recommendation_returns_null_when_tmdb_has_no_results(): void
    {
        $tmdb = Mockery::mock(TMDbServiceInterface::class);
        $filters = ['genre_id' => 28, 'year' => 2024];

        $tmdb->shouldReceive('discoverMovies')
            ->once()
            ->with($filters)
            ->andReturn(['results' => [], 'total_pages' => 0]);
        $tmdb->shouldReceive('getMovieTrailer')->never();

        $result = (new MovieService($tmdb))->getRecommendation($filters, 7);

        self::assertNull($result);
    }

    public function test_get_recommendation_formats_movie_and_saves_history(): void
    {
        $genreModel = Mockery::mock('alias:'.Genre::class);
        $historyModel = Mockery::mock('alias:'.SearchHistory::class);
        $tmdb = Mockery::mock(TMDbServiceInterface::class);
        $genreQuery = Mockery::mock();

        $filters = [
            'genre_id' => 28,
            'year' => 2024,
            'min_rating' => 7.0,
        ];
        $movie = [
            'id' => 101,
            'title' => 'Example Movie',
            'overview' => 'Film untuk pengujian.',
            'poster_path' => '/poster.jpg',
            'backdrop_path' => '/backdrop.jpg',
            'release_date' => '2024-05-10',
            'vote_average' => 7.84,
            'vote_count' => 1500,
            'genre_ids' => [28, 12],
        ];
        $localGenre = (object) ['id' => 3];

        $tmdb->shouldReceive('discoverMovies')
            ->once()
            ->with($filters)
            ->andReturn(['results' => [$movie], 'total_pages' => 1]);
        $tmdb->shouldReceive('getMovieTrailer')
            ->once()
            ->with(101)
            ->andReturn('https://youtube.test/trailer');
        $tmdb->shouldReceive('getPosterUrl')
            ->once()
            ->with('/poster.jpg')
            ->andReturn('https://image.test/poster.jpg');

        $genreModel->shouldReceive('where')
            ->once()
            ->with('tmdb_genre_id', 28)
            ->andReturn($genreQuery);
        $genreQuery->shouldReceive('first')->once()->andReturn($localGenre);

        $historyModel->shouldReceive('create')
            ->once()
            ->with(Mockery::on(static function (array $data): bool {
                return $data['user_id'] === 7
                    && $data['genre_id'] === 3
                    && $data['year'] === 2024
                    && $data['rating'] === 7.0
                    && isset($data['searched_at']);
            }));

        $result = (new MovieService($tmdb))->getRecommendation($filters, 7);

        self::assertSame(101, $result['id']);
        self::assertSame('Example Movie', $result['title']);
        self::assertSame('https://image.tmdb.org/t/p/w500/poster.jpg', $result['poster_url']);
        self::assertSame('https://image.tmdb.org/t/p/w1280/backdrop.jpg', $result['backdrop_url']);
        self::assertSame('2024', $result['release_year']);
        self::assertSame(7.8, $result['rating']);
        self::assertTrue($result['trailer_available']);
    }

    public function test_get_detail_formats_genres_cast_and_gallery(): void
    {
        $tmdb = Mockery::mock(TMDbServiceInterface::class);

        $tmdb->shouldReceive('getMovieDetail')
            ->once()
            ->with(101)
            ->andReturn([
                'id' => 101,
                'title' => 'Example Movie',
                'overview' => 'Film untuk pengujian.',
                'poster_path' => '/poster.jpg',
                'backdrop_path' => '/backdrop.jpg',
                'release_date' => '2024-05-10',
                'vote_average' => 8.26,
                'vote_count' => 300,
                'genres' => [
                    ['id' => 28, 'name' => 'Action'],
                ],
                'credits' => [
                    'cast' => [
                        [
                            'id' => 1,
                            'name' => 'Actor One',
                            'character' => 'Hero',
                            'profile_path' => '/actor.jpg',
                        ],
                    ],
                ],
            ]);

        $tmdb->shouldReceive('getMovieImages')
            ->once()
            ->with(101)
            ->andReturn([
                ['file_path' => '/gallery.jpg', 'width' => 1280, 'height' => 720],
            ]);

        $tmdb->shouldReceive('getPosterUrl')
            ->once()
            ->with('/poster.jpg')
            ->andReturn('https://image.test/poster.jpg');

        $result = (new MovieService($tmdb))->getDetail(101);

        self::assertSame('2024', $result['release_year']);
        self::assertSame(8.3, $result['rating']);
        self::assertSame([
            ['id' => 28, 'name' => 'Action'],
        ], $result['genres']);
        self::assertSame('https://image.tmdb.org/t/p/w185/actor.jpg', $result['cast'][0]['profile_url']);
        self::assertSame('https://image.tmdb.org/t/p/w780/gallery.jpg', $result['gallery'][0]['image_url']);
    }
}
