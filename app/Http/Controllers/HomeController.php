<?php

namespace App\Http\Controllers;

use App\Contracts\FavoriteServiceInterface;
use App\Contracts\GenreServiceInterface;
use App\Contracts\HistoryServiceInterface;
use App\Contracts\MovieServiceInterface;
use App\Exceptions\TMDbException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly MovieServiceInterface    $movieService,
        private readonly GenreServiceInterface    $genreService,
        private readonly FavoriteServiceInterface $favoriteService,
        private readonly HistoryServiceInterface  $historyService,
    ) {}

    /**
     * GET /
     * Tampilkan beranda. Jika ada query string dari pencarian,
     * langsung proses dan tampilkan hasil.
     */
    public function index(Request $request): View
    {
        // Genre untuk dropdown filter — GenreService yang handle caching & sinkron DB
        $genres = $this->safeGetGenres();

        $movie   = null;
        $error   = null;
        $filters = [];

        if ($request->hasAny(['genre_id', 'genre_id_2', 'year', 'country'])) {
            [$movie, $error, $filters] = $this->processSearch($request);
        }

        return view('pages.home', compact('genres', 'movie', 'error', 'filters'));
    }

    /**
     * POST /search
     * Validasi lalu redirect ke GET / dengan query string.
     * Pola ini membuat URL bisa di-bookmark dan tombol "Cari lagi"
     * cukup submit form yang sama tanpa logika JS tambahan.
     */
    public function search(Request $request): RedirectResponse
    {
        $request->validate([
            'genre_id'   => ['nullable', 'integer'],
            'genre_id_2' => ['nullable', 'integer'],
            'year'       => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 2)],
            'country'    => ['nullable', 'string', 'max:2'],
        ]);

        $params = array_filter([
            'genre_id'   => $request->input('genre_id'),
            'genre_id_2' => $request->input('genre_id_2'),
            'year'       => $request->input('year'),
            'country'    => $request->input('country'),
            '_r'         => rand(1000, 9999), // seed acak agar hasil bisa beda tiap submit
        ]);

        return redirect()->route('home', $params);
    }

    // ── Private helpers ───────────────────────────────────────────

    private function processSearch(Request $request): array
    {
        $filters = [
            'genre_id'   => $request->input('genre_id'),
            'genre_id_2' => $request->input('genre_id_2'),
            'year'       => $request->input('year'),
            'country'    => $request->input('country'),
        ];

        try {
            $movie = $this->movieService->getRecommendation($filters);

            if (!$movie) {
                return [
                    null,
                    'Tidak ada film yang cocok dengan filter ini. Coba ubah pilihan.',
                    $filters,
                ];
            }

            // Simpan ke riwayat pencarian
            $this->historyService->store(auth()->id(), $filters);

            // Tandai apakah film ini sudah difavoritkan user
            $movie['is_favorited'] = $this->favoriteService->isFavorited(
                auth()->id(),
                $movie['id']
            );

            return [$movie, null, $filters];

        } catch (TMDbException $e) {
            return [null, $e->getMessage(), $filters];
        }
    }

    private function safeGetGenres(): array
    {
        try {
            return $this->genreService->all();
        } catch (TMDbException) {
            return [];
        }
    }
}
