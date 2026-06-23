import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

import MainLayout from "../layouts/MainLayout";

import { getMovie } from "../api/movies";

import CastList from "../components/movie/CastList";
import FavoriteButton from "../components/movie/FavoriteButton";
import TrailerModal from "../components/movie/TrailerModal";
import Gallery from "../components/movie/Gallery";

export default function MovieDetailPage() {
    const { movieId } = useParams();

    const [movie, setMovie] = useState(null);
    const [showTrailer, setShowTrailer] = useState(false);

    useEffect(() => {
        async function loadMovie() {
            try {
                const response = await getMovie(movieId);
                if (response) {
                    setMovie(response);
                }
            } catch (error) {
                console.error(error);
            }
        }

        loadMovie();
    }, [movieId]);

    if (!movie) return null;

    return (
        <MainLayout>
            <section className="movie-detail">
                <img src={movie.poster_url} alt={movie.title} />

                <h1>{movie.title}</h1>

                <p>{movie.overview || "Sinopsis belum tersedia."}</p>

                <p>⭐ {movie.rating}</p>

                <p>{movie.release_year}</p>

                <p>{movie.vote_count} suara</p>

                <button onClick={() => setShowTrailer(true)}>
                    Tonton Trailer
                </button>

                <FavoriteButton movie={movie} />

                {movie.gallery?.length > 0 && (
                    <Gallery images={movie.gallery} />
                )}

                {movie.cast?.length > 0 && <CastList cast={movie.cast} />}

                {movie.trailer_key ? (
                    <TrailerModal
                        open={showTrailer}
                        trailerKey={movie.trailer_key}
                        onClose={() => setShowTrailer(false)}
                    />
                ) : (
                    <p>Trailer belum tersedia.</p>
                )}
            </section>
        </MainLayout>
    );
}
