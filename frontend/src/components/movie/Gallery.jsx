export default function Gallery({ images = [] }) {
    if (!images.length) {
        return null;
    }

    return (
        <section className="gallery-section">
            <h2>Galeri</h2>

            <div className="gallery-grid">
                {images.map((image, index) => {
                    const imageUrl =
                        image.file_path ||
                        image.image_url ||
                        image.url ||
                        image;

                    return (
                        <div key={index} className="gallery-item">
                            <img
                                src={imageUrl}
                                alt={`Gallery ${index + 1}`}
                                loading="lazy"
                            />
                        </div>
                    );
                })}
            </div>
        </section>
    );
}
