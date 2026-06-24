import { useState } from "react";
import { useNavigate } from "react-router-dom";

import toast from "react-hot-toast";

import MainLayout from "../layouts/MainLayout";
import FilterPanel from "../components/movie/FilterPanel";
import FeaturedMovie from "../components/movie/FeaturedMovie";

import { getRecommendation } from "../api/movies";

import { useAuth } from "../hooks/useAuth";

export default function DiscoverPage() {
    const [movie, setMovie] = useState(null);
    const [loading, setLoading] = useState(false);

    const { user } = useAuth();

    const navigate = useNavigate();

    async function handleRecommendation(filters) {
        // CEK LOGIN DULU
        if (!user) {
            toast.error(
                "Silakan login terlebih dahulu untuk mendapatkan rekomendasi film.",
            );

            setTimeout(() => {
                navigate("/login");
            }, 1200);

            return;
        }

        setLoading(true);

        try {
            const response = await getRecommendation(filters);

            if (response) {
                setMovie(response);
            }
        } catch (error) {
            console.error("Gagal mendapatkan rekomendasi film:", error);

            toast.error("Gagal mendapatkan rekomendasi film.");
        } finally {
            setLoading(false);
        }
    }

    return (
        <MainLayout>
            <header className="hero">
                <h1>Temukan Film Malam Ini</h1>

                <p>
                    Pilih genre, tahun, dan rating untuk mendapatkan rekomendasi
                    terbaik.
                </p>

                <FilterPanel onSubmit={handleRecommendation} />
            </header>

            {loading ? (
                <div
                    style={{
                        textAlign: "center",
                        padding: "3rem",
                        color: "#94a3b8",
                    }}
                >
                    <p className="animate-pulse">
                        Sedang mencocokkan film terbaik untuk Anda... 🎬
                    </p>
                </div>
            ) : (
                movie && <FeaturedMovie movie={movie} />
            )}
        </MainLayout>
    );
}
