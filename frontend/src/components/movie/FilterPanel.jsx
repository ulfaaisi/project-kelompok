import { useState, useEffect } from "react";
import { request } from "../../api/client";

export default function FilterPanel({ onSubmit }) {
    const [genres, setGenres] = useState([]);
    const [genre, setGenre] = useState("");
    const [year, setYear] = useState("");
    const [rating, setRating] = useState("");
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        async function loadGenres() {
            try {
                const result = await request("/api/genres");
                if (result) {
                    setGenres(result);
                }
            } catch (error) {
                console.error("Gagal memuat list genre di FilterPanel:", error);
            } finally {
                setLoading(false);
            }
        }
        loadGenres();
    }, []);

    const handleSubmit = (e) => {
        e.preventDefault();
        onSubmit({
            genre,
            year,
            rating,
        });
    };

    const years = [];
    for (let y = new Date().getFullYear(); y >= 1980; y--) {
        years.push(y);
    }

    return (
        /* PERBAIKAN:
          Kita bungkus dengan <div> yang memiliki inline style 'margin-top' dan 'position: relative'.
          Ini memaksa kotak filter turun ke bawah mengikuti urutan dokumen (tidak melayang)
          dan memberikan jarak aman sebesar 40px dari teks judul di atasnya.
        */
        <div
            style={{
                position: "relative",
                marginTop: "65px",
                marginBottom: "20px",
                width: "100%",
                zIndex: 10,
            }}
        >
            {/* Class asli 'filter-panel container' kita kembalikan agar stylenya muncul lagi */}
            <form className="filter-panel container" onSubmit={handleSubmit}>
                {/* Dropdown Genre */}
                <select
                    value={genre}
                    onChange={(e) => setGenre(e.target.value)}
                    disabled={loading}
                    style={{ backgroundColor: "#1e293b", color: "#ffffff" }} // Proteksi warna tulisan agar tidak putih di atas putih
                >
                    <option
                        value=""
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        {loading ? "Memuat Genre..." : "Semua Genre"}
                    </option>

                    {!loading &&
                        genres.map((g) => (
                            <option
                                key={g.tmdb_genre_id || g.id}
                                value={g.tmdb_genre_id || g.id}
                                style={{
                                    backgroundColor: "#1e293b",
                                    color: "#ffffff",
                                }}
                            >
                                {g.name}
                            </option>
                        ))}
                </select>

                {/* Dropdown Tahun */}
                <select
                    value={year}
                    onChange={(e) => setYear(e.target.value)}
                    style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                >
                    <option
                        value=""
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        Semua Tahun
                    </option>
                    {years.map((y) => (
                        <option
                            key={y}
                            value={y}
                            style={{
                                backgroundColor: "#1e293b",
                                color: "#ffffff",
                            }}
                        >
                            {y}
                        </option>
                    ))}
                </select>

                {/* Dropdown Rating */}
                <select
                    value={rating}
                    onChange={(e) => setRating(e.target.value)}
                    style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                >
                    <option
                        value=""
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        Semua Rating
                    </option>
                    <option
                        value="5"
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        5+
                    </option>
                    <option
                        value="6"
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        6+
                    </option>
                    <option
                        value="7"
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        7+
                    </option>
                    <option
                        value="8"
                        style={{ backgroundColor: "#1e293b", color: "#ffffff" }}
                    >
                        8+
                    </option>
                </select>

                <button type="submit" disabled={loading}>
                    Cari Rekomendasi
                </button>
            </form>
        </div>
    );
}
