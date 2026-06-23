export default function TrailerModal({ trailerKey, open, onClose }) {
    if (!open) return null;

    return (
        <div className="modal-backdrop">
            <div className="modal-content">
                <button onClick={onClose}>Tutup</button>

                <iframe
                    width="100%"
                    height="500"
                    src={`https://www.youtube.com/embed/${trailerKey}`}
                    allowFullScreen
                    title="Trailer"
                />
            </div>
        </div>
    );
}
