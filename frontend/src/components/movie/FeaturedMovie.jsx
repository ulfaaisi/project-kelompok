import { useState } from "react";

import FavoriteButton from "./FavoriteButton";
import TrailerModal from "./TrailerModal";

export default function FeaturedMovie({ movie }) {
    const [showTrailer, setShowTrailer] = useState(false);

    if (!movie) return null;

    return (
        <>
            <section
                className="featured-movie"
                style={{
                    backgroundImage: `url(${movie.backdrop_url})`,
                }}
            >
                <div className="overlay">
                    <img src={movie.poster_url} alt={movie.title} />

                    <div className="featured-content">
                        <div className="movie-meta">
                            <span className="movie-badge">
                                ⭐ {movie.rating}
                            </span>

                            <span className="movie-badge">
                                {movie.release_year}
                            </span>
                        </div>

                        <h2>{movie.title}</h2>

                        <p className="movie-overview">
                            {movie.overview || "Sinopsis belum tersedia."}
                        </p>

                        <div className="featured-actions">
                            <FavoriteButton movie={movie} />

                            {movie.trailer_available && (
                                <button
                                    className="watch-trailer-btn"
                                    onClick={() => setShowTrailer(true)}
                                >
                                    ▶ Trailer
                                </button>
                            )}
                        </div>
                    </div>

                    <div className="rating-circle">
                        {Math.round(movie.rating)}
                    </div>
                </div>
            </section>

            <TrailerModal
                open={showTrailer}
                trailerUrl={movie.trailer_url}
                onClose={() => setShowTrailer(false)}
            />
        </>
    );
}
