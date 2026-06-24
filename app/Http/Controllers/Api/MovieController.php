<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\MovieServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function __construct(
        private MovieServiceInterface $movieService
    ) {}

    public function recommendation(Request $request): JsonResponse
    {
        $filters = $request->all();

        $userId = $request->user()?->id;

        $movie = $this->movieService->getRecommendation(
            $filters,
            $userId
        );

        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada rekomendasi film yang cocok dengan filter tersebut.'
            ], 404);
        }

        // fallback overview kosong
        if (
            !isset($movie['overview']) ||
            empty(trim($movie['overview']))
        ) {
            $movie['overview'] =
                'Sinopsis belum tersedia untuk film ini.';
        }
      
        return response()->json([
            'success' => true,
            'data' => $movie
        ]);
    }

    public function show(int $movieId): JsonResponse
    {
        $movie = $this->movieService->getDetail($movieId);

        return response()->json([
            'success' => true,
            'data' => $movie
        ]);
    }
}
