<?php

namespace App\Http\Controllers;

use App\Contracts\FavoriteServiceInterface;
use App\Contracts\MovieServiceInterface;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function __construct(
        private readonly MovieServiceInterface $movieService,
        private readonly FavoriteServiceInterface $favoriteService,
    ) {}

    public function show(int $id): View
    {
        $movie = $this->movieService->getDetail($id);

        $movie['is_favorited'] =
            auth()->check()
                ? $this->favoriteService->isFavorited(
                    auth()->id(),
                    $id
                )
                : false;

        return view(
            'pages.movie-detail',
            compact('movie')
        );
    }
}
