<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\User;
use App\Services\FavoriteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_favorite()
    {
        $user = User::create([
            'name' => 'Uswah',
            'email' => 'uswah@test.com',
            'password' => bcrypt('password'),
        ]);

        $service = new FavoriteService();

        $result = $service->addFavorite(
            $user->id,
            [
                'movie_id' => 1,
                'movie_title' => 'Test Movie',
                'rating' => 8.5,
            ]
        );

        $this->assertFalse(
            $result['already_exists']
        );

        $this->assertDatabaseHas(
            'favorites',
            [
                'movie_id' => 1,
                'movie_title' => 'Test Movie',
            ]
        );
    }
}