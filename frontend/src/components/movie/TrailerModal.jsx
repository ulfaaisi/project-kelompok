import { useEffect } from "react";

export default function TrailerModal({ open, onClose, trailerUrl }) {
    useEffect(() => {
        const handler = (e) => {
            if (e.key === "Escape") {
                onClose();
            }
        };

        document.addEventListener("keydown", handler);

        return () => document.removeEventListener("keydown", handler);
    }, [onClose]);

    if (!open || !trailerUrl) {
        return null;
    }

    const embedUrl = trailerUrl
        .replace(
            "https://www.youtube.com/watch?v=",
            "https://www.youtube.com/embed/",
        )
        .split("&")[0];

    return (
        <div className="modal-backdrop" onClick={onClose}>
            <div className="modal-content" onClick={(e) => e.stopPropagation()}>
                <button className="modal-close" onClick={onClose}>
                    ✕
                </button>

                <iframe src={embedUrl} title="Trailer" allowFullScreen />
            </div>
        </div>
    );
}
