import { useState } from "react";

import MainLayout from "../layouts/MainLayout";
import FilterPanel from "../components/movie/FilterPanel";
import FeaturedMovie from "../components/movie/FeaturedMovie";

import { getRecommendation } from "../api/movies";

export default function DiscoverPage() {
    const [movie, setMovie] = useState(null);
    const [loading, setLoading] = useState(false); // Tambahan state loading untuk UI yang lebih smooth

    async function handleRecommendation(filters) {
        setLoading(true);
        try {
            const response = await getRecommendation(filters);

            /* PERBAIKAN BUG UTAMA:
              client.js kita sekarang sudah otomatis membongkar result.data.
              Jadi, variabel 'response' di sini sudah berisi objek film bersih.
              Ubah dari 'response.data' menjadi langsung 'response'.
            */
            if (response) {
                setMovie(response);
            }
        } catch (error) {
            console.error("Gagal mendapatkan rekomendasi film:", error);
        } finally {
            setLoading(false);
        }
    }

    return (
        <MainLayout>
            {/* PERBAIKAN CSS CLASS:
              Ubah dari 'hero-section' menjadi 'hero' agar terikat sempurna
              dengan CSS display: flex, text-align: center, dll yang sudah kita rapikan tadi.
            */}
            <header className="hero">
                <h1>Temukan Film Malam Ini</h1>
                <p>
                    Pilih genre, tahun, dan rating untuk mendapatkan rekomendasi
                    terbaik.
                </p>

                {/* FilterPanel dipanggil di sini agar otomatis tersusun rata tengah */}
                <FilterPanel onSubmit={handleRecommendation} />
            </header>

            {/* Indikator visual saat sistem sedang memproses data film acak */}
            {loading ? (
                <div
                    style={{
                        textAlign: "center",
                        padding: "3rem",
                        color: "#94a3b8",
                    }}
                >
                    <p className="animate-pulse">
                        Sedangkan mencocokkan film terbaik untuk Anda... 🎬
                    </p>
                </div>
            ) : (
                /* Komponen penampil film hanya muncul jika data movie sudah ada */
                movie && <FeaturedMovie movie={movie} />
            )}
        </MainLayout>
    );
}
