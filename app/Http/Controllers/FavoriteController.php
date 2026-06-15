<?php

namespace App\Http\Controllers;

use App\Contracts\FavoriteServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function __construct(
        private readonly FavoriteServiceInterface $favoriteService
    ) {}

    public function index(): View
    {
        $favorites = $this->favoriteService->getUserFavorites(auth()->id());
        return view('pages.favorites', compact('favorites'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'movie_id'     => ['required', 'integer'],
            'movie_title'  => ['required', 'string', 'max:500'],
            'poster_path'  => ['nullable', 'string'],
            'release_year' => ['nullable', 'string', 'size:4'],
            'rating'       => ['nullable', 'numeric', 'min:0', 'max:10'],
        ]);

        $result = $this->favoriteService->addFavorite(auth()->id(), $data);

        if ($result['already_exists']) {
            return back()->with('warning', 'Film ini sudah ada di daftar favorit.');
        }

        return back()->with('success', 'Film berhasil ditambahkan ke favorit.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $deleted = $this->favoriteService->removeFavorite($id, auth()->id());

        if (!$deleted) {
            return back()->with('error', 'Favorit tidak ditemukan.');
        }

        return back()->with('success', 'Film dihapus dari favorit.');
    }
}
