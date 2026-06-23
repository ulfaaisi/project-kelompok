<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\MovieServiceInterface;
use Illuminate\Http\JsonResponse; // Tambahkan ini agar return type konsisten
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function __construct(
        private MovieServiceInterface $movieService
    ) {}

    public function recommendation(Request $request): JsonResponse
    {
        // 1. Ambil semua input filter dari request frontend/Postman
        $filters = $request->all();

        // 2. Ambil ID user yang sedang login dari session Sanctum
        $userId = $request->user()->id;

        // 3. Panggil fungsi yang benar (getRecommendation) dengan 2 parameter
        $movies = $this->movieService->getRecommendation($filters, $userId);

        if (!$movies) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada rekomendasi film yang cocok dengan filter tersebut.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $movies
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
