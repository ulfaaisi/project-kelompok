export default function MovieGallery({ images = [] }) {
    return (
        <section className="movie-gallery">
            {images.map((image, index) => (
                <img
                    key={index}
                    src={image.file_path}
                    alt={`Gallery ${index}`}
                />
            ))}
        </section>
    );
}
