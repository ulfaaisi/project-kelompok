<?php

namespace App\Http\Controllers;

use App\Contracts\GenreServiceInterface;
use App\Exceptions\TMDbException;
use Illuminate\Http\JsonResponse;

/**
 * GenreController
 *
 * Bertanggung jawab untuk endpoint genre.
 * Saat ini hanya dipakai internal (genre diambil di HomeController untuk dropdown),
 * tapi controller ini disiapkan jika nanti dibutuhkan endpoint JSON
 * atau halaman admin genre tersendiri.
 */
class GenreController extends Controller
{
    public function __construct(
        private readonly GenreServiceInterface $genreService
    ) {}

    /**
     * GET /genres (JSON)
     * Endpoint untuk mengambil semua genre.
     * Berguna jika nanti frontend butuh refresh genre tanpa reload halaman.
     */
    public function index(): JsonResponse
    {
        try {
            $genres = $this->genreService->all();

            return response()->json([
                'success' => true,
                'data'    => $genres,
            ]);

        } catch (TMDbException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
            ], 503);
        }
    }
}
