import { useEffect, useState } from "react";
import { getGenres, getRecommendation } from "../api/movies";

import FilterPanel from "../components/movie/FilterPanel";
import FeaturedMovie from "../components/movie/FeaturedMovie";

export default function HomePage() {
    const [genres, setGenres] = useState([]);
    const [selectedGenres, setSelectedGenres] = useState([]);
    const [movie, setMovie] = useState(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        loadGenres();
    }, []);

    async function loadGenres() {
        const data = await getGenres();
        setGenres(data);
    }

    async function handleRecommend() {
        setLoading(true);

        try {
            const data = await getRecommendation(
                selectedGenres.map((genre) => genre.tmdb_genre_id),
            );

            setMovie(data);
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="container">
            <section className="hero">
                <h1>Temukan Film Sesuai Selera Anda</h1>

                <p>
                    Pilih genre favorit dan dapatkan rekomendasi film terbaik.
                </p>
            </section>

            <FilterPanel
                genres={genres}
                selectedGenres={selectedGenres}
                setSelectedGenres={setSelectedGenres}
                onSubmit={handleRecommend}
            />

            {loading && <p>Memuat rekomendasi...</p>}

            {!loading && movie && <FeaturedMovie movie={movie} />}

            {!loading && !movie && (
                <p>Pilih genre untuk mendapatkan rekomendasi.</p>
            )}
        </div>
    );
}
