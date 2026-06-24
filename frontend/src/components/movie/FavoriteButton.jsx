import { useState } from "react";

import { addFavorite } from "../../api/favorites";

import toast from "react-hot-toast";

export default function FavoriteButton({ movie }) {
    const [loading, setLoading] = useState(false);

    async function handleFavorite() {
        try {
            setLoading(true);

            await addFavorite({
                movie_id: movie.id,
                movie_title: movie.title,
                poster_path: movie.poster_path || movie.poster_url,
                release_year: movie.release_year,
                rating: movie.rating,
            });

            toast.success("Film berhasil ditambahkan");
        } catch {
            toast.error("Gagal menambahkan favorit");
        } finally {
            setLoading(false);
        }
    }

    return (
        <button
            className="favorite-btn"
            onClick={handleFavorite}
            disabled={loading}
        >
            {loading ? "Menyimpan..." : "❤ Tambah Favorit"}
        </button>
    );
}
