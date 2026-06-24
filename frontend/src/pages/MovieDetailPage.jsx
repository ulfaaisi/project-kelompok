import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

import MainLayout from "../layouts/MainLayout";

import { getMovie } from "../api/movies";

import FeaturedMovie from "../components/movie/FeaturedMovie";
// import CastList from "../components/movie/CastList";
// import Gallery from "../components/movie/Gallery";
// import TrailerModal from "../components/movie/TrailerModal";

import SkeletonCard from "../components/common/SkeletonCard";

export default function MovieDetailPage() {
    const { movieId } = useParams();

    const [movie, setMovie] = useState(null);
    const [loading, setLoading] = useState(true);

    const [showTrailer, setShowTrailer] = useState(false);

    useEffect(() => {
        loadMovie();
    }, [movieId]);

    async function loadMovie() {
        try {
            const data = await getMovie(movieId);

            setMovie(data);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    }

    if (loading) {
        return (
            <MainLayout>
                <div className="movie-grid">
                    {[...Array(3)].map((_, i) => (
                        <SkeletonCard key={i} />
                    ))}
                </div>
            </MainLayout>
        );
    }

    if (!movie) {
        return (
            <MainLayout>
                <div className="empty-state">Film tidak ditemukan.</div>
            </MainLayout>
        );
    }

    return (
        <MainLayout>
            <FeaturedMovie movie={movie} />

            {/* {movie.trailer_available && (
                <div
                    style={{
                        display: "flex",
                        justifyContent: "center",
                        marginBottom: "2rem",
                    }}
                >
                    <button
                        className="watch-trailer-btn"
                        onClick={() => setShowTrailer(true)}
                    >
                        ▶ Watch Trailer
                    </button>
                </div>
            )} */}

            {/* {movie.gallery?.length > 0 && <Gallery images={movie.gallery} />}

            {movie.cast?.length > 0 && <CastList cast={movie.cast} />} */}

            {/* <TrailerModal
                open={showTrailer}
                trailerUrl={movie.trailer_url}
                onClose={() => setShowTrailer(false)}
            /> */}
        </MainLayout>
    );
}
