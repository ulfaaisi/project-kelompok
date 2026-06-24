export default function SkeletonCard() {
    return (
        <div className="movie-card skeleton-card">
            <div
                className="skeleton"
                style={{
                    height: 320,
                    borderRadius: 16,
                    marginBottom: 12,
                }}
            />

            <div
                className="skeleton"
                style={{
                    height: 20,
                    width: "80%",
                    marginBottom: 8,
                }}
            />

            <div
                className="skeleton"
                style={{
                    height: 16,
                    width: "50%",
                }}
            />
        </div>
    );
}
