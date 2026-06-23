import FavoriteButton from "./FavoriteButton";

export default function FeaturedMovie({ movie }) {
  if (!movie) return null;

  return (
    <section
      className="featured-movie"
      style={{
        backgroundImage: `url(${movie.backdrop_url})`,
      }}
    >
      <div className="overlay">
        <img
          src={movie.poster_url}
          alt={movie.title}
        />

        <div className="featured-content">

          <div className="movie-meta">
            <span className="movie-badge">
              ⭐ {movie.rating}
            </span>

            <span className="movie-badge">
              {movie.release_year}
            </span>
          </div>

          <h2>
            {movie.title}
          </h2>

          <p className="movie-overview">
            {movie.overview ||
              "Tidak ada deskripsi tersedia."}
          </p>

          <div
            style={{
              display: "flex",
              gap: "1rem",
              marginTop: "2rem",
              flexWrap: "wrap",
            }}
          >
            <FavoriteButton movie={movie} />

            {movie.trailer_available && (
              <button className="trailer-btn">
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
  );
}
