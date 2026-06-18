<?php

namespace App\Http\Controllers;

use App\Contracts\GenreServiceInterface;
use App\Exceptions\TMDbException;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    public function __construct(
        private readonly GenreServiceInterface $genreService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $genres = $this->genreService->getAllGenres();

            return response()->json([
                'success' => true,
                'data' => $genres,
            ]);

        } catch (TMDbException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 503);
        }
    }
}