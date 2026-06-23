import { useEffect, useState } from "react";
import { addFavorite, getFavorites, removeFavorite } from "../../api/favorites";

export default function FavoriteButton({ movie }) {
    const [favorites, setFavorites] = useState([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        loadFavorites();
    }, []);

    async function loadFavorites() {
        try {
            const data = await getFavorites();
            setFavorites(data);
        } catch {
            setFavorites([]);
        }
    }

    const isFavorite = favorites.some((item) => item.movie_id === movie.id);

    async function handleClick() {
        setLoading(true);

        try {
            if (isFavorite) {
                await removeFavorite(movie.id);

                setFavorites((prev) =>
                    prev.filter((item) => item.movie_id !== movie.id),
                );
            } else {
                const favorite = await addFavorite(movie);

                setFavorites((prev) => [...prev, favorite]);
            }
        } finally {
            setLoading(false);
        }
    }

    return (
        <button onClick={handleClick} disabled={loading}>
            {isFavorite ? "❤️ Favorit" : "🤍 Tambah Favorit"}
        </button>
    );
}
