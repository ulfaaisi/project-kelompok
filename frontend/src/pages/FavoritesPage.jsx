import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

const API_URL = import.meta.env.VITE_API_URL;

export default function FavoritesPage() {
    const [favorites, setFavorites] = useState([]);

    useEffect(() => {
        loadFavorites();
    }, []);

    async function loadFavorites() {
        const response = await fetch(`${API_URL}/api/favorites`, {
            credentials: "include",
        });

        const result = await response.json();

        setFavorites(result.data || []);
    }

    return (
        <section className="container">
            <h1>Film Favorit</h1>

            <div className="movie-grid">
                {favorites.map((movie) => (
                    <Link
                        key={movie.id}
                        to={`/movies/${movie.movie_id}`}
                        className="movie-card"
                    >
                        <img
                            src={`https://image.tmdb.org/t/p/w500${movie.poster_path}`}
                            alt={movie.movie_title}
                        />

                        <h3>{movie.movie_title}</h3>

                        <p>{movie.release_year}</p>

                        <span>⭐ {movie.rating}</span>
                    </Link>
                ))}
            </div>
        </section>
    );
}
