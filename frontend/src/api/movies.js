const API_URL = import.meta.env.VITE_API_URL;

export async function getGenres() {
    const response = await fetch(`${API_URL}/api/genres`, {
        credentials: "include",
    });

    const result = await response.json();

    return result.data;
}

export async function getRecommendation(genreIds = []) {
    const response = await fetch(`${API_URL}/api/movies/recommendation`, {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            genre_ids: genreIds,
        }),
    });

    const result = await response.json();

    return result.data;
}

export async function getMovie(movieId) {
    const response = await fetch(`${API_URL}/api/movies/${movieId}`, {
        credentials: "include",
    });

    const result = await response.json();

    return result.data;
}
