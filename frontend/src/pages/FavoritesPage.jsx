import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

import MainLayout from "../layouts/MainLayout";

import { getFavorites, removeFavorite } from "../api/favorites";

import EmptyState from "../components/common/EmptyState";
import SkeletonCard from "../components/common/SkeletonCard";

import toast from "react-hot-toast";

export default function FavoritesPage() {
    const [movies, setMovies] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        loadFavorites();
    }, []);

    async function loadFavorites() {
        try {
            setLoading(true);

            const data = await getFavorites();

            setMovies(data || []);
        } catch (error) {
            console.error(error);

            setMovies([]);
        } finally {
            setLoading(false);
        }
    }

    async function handleRemove(favoriteId) {
        try {
            await removeFavorite(favoriteId);

            setMovies((prev) =>
                prev.filter((movie) => movie.id !== favoriteId),
            );

            toast.success("Film berhasil dihapus dari favorit");
        } catch (error) {
            console.error(error);

            toast.error("Gagal menghapus favorit");
        }
    }

    return (
        <MainLayout>
            <section className="page-section">
                <div className="page-header">
                    <h1>Film Favorit</h1>

                    <p>Daftar film yang telah Anda simpan.</p>
                </div>

                {loading && (
                    <div className="movie-grid">
                        {[...Array(8)].map((_, i) => (
                            <SkeletonCard key={i} />
                        ))}
                    </div>
                )}

                {!loading && movies.length === 0 && (
                    <EmptyState
                        title="Belum Ada Favorit"
                        message="Tambahkan film ke favorit terlebih dahulu."
                    />
                )}

                {!loading && movies.length > 0 && (
                    <div className="movie-grid">
                        {movies.map((movie) => (
                            <div key={movie.id} className="movie-card">
                                <Link to={`/movies/${movie.movie_id}`}>
                                    <img
                                        src={
                                            movie.poster_path?.startsWith(
                                                "http",
                                            )
                                                ? movie.poster_path
                                                : `https://image.tmdb.org/t/p/w500${movie.poster_path}`
                                        }
                                        alt={movie.movie_title}
                                    />
                                </Link>

                                <div className="movie-card-content">
                                    <Link
                                        to={`/movies/${movie.movie_id}`}
                                        className="movie-title-link"
                                    >
                                        <h3>{movie.movie_title}</h3>
                                    </Link>

                                    <div className="movie-meta">
                                        <span>⭐ {movie.rating}</span>

                                        <span>{movie.release_year}</span>
                                    </div>

                                    <button
                                        className="danger-btn"
                                        onClick={() => handleRemove(movie.id)}
                                    >
                                        Hapus Favorit
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </section>
        </MainLayout>
    );
}
