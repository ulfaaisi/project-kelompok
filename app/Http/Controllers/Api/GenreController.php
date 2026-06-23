<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\GenreServiceInterface;

class GenreController extends Controller
{
    public function __construct(
        private GenreServiceInterface $genreService
    ) {}

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->genreService->getAllGenres()
        ]);
    }
}
