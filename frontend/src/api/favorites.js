const API_URL = import.meta.env.VITE_API_URL;

export async function getFavorites() {
    const response = await fetch(`${API_URL}/api/favorites`, {
        credentials: "include",
    });

    const result = await response.json();

    return result.data;
}

export async function addFavorite(movie) {
    const response = await fetch(`${API_URL}/api/favorites`, {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            movie_id: movie.id,
            movie_title: movie.title,
            poster_path: movie.poster_url.replace(
                "https://image.tmdb.org/t/p/w500",
                "",
            ),
            release_year: movie.release_year,
            rating: movie.rating,
        }),
    });

    const result = await response.json();

    return result.data;
}

export async function removeFavorite(movieId) {
    await fetch(`${API_URL}/api/favorites/${movieId}`, {
        method: "DELETE",
        credentials: "include",
        headers: {
            Accept: "application/json",
        },
    });
}
