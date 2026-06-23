<?php

namespace App\Http\Controllers\Api; // Pastikan namespace sesuai dengan folder Api

use App\Http\Controllers\Controller;
use App\Contracts\FavoriteServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(
        private readonly FavoriteServiceInterface $favoriteService
    ) {}

    // 1. Mengambil data favorit (GET /api/favorites)
    public function index(): JsonResponse
    {
        $favorites = $this->favoriteService->getUserFavorites(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $favorites
        ], 200);
    }

    // 2. Menambahkan ke favorit (POST /api/favorites)
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'movie_id'     => ['required', 'integer'],
            'movie_title'  => ['required', 'string', 'max:500'],
            'poster_path'  => ['nullable', 'string'],
            'release_year' => ['nullable', 'string', 'size:4'],
            'rating'       => ['nullable', 'numeric', 'min:0', 'max:10'],
        ]);

        // Memanggil addFavorite sesuai yang ada di FavoriteService
        $result = $this->favoriteService->addFavorite(auth()->id(), $data);

        if ($result['already_exists']) {
            return response()->json([
                'success' => false,
                'message' => 'Film ini sudah ada di daftar favorit.'
            ], 409); // 409 Conflict
        }

        return response()->json([
            'success' => true,
            'message' => 'Film berhasil ditambahkan ke favorit.',
            'data' => $result['favorite']
        ], 201); // 201 Created
    }

    // 3. Menghapus dari favorit (DELETE /api/favorites/{movieId})
    public function destroy(Request $request, int $id): JsonResponse
    {
        $deleted = $this->favoriteService->removeFavorite($id, auth()->id());

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Favorit tidak ditemukan.'
            ], 404); // 404 Not Found
        }

        return response()->json([
            'success' => true,
            'message' => 'Film dihapus dari favorit.'
        ], 200);
    }
}
