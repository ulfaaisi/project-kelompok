import { Routes, Route } from "react-router-dom";

import DiscoverPage from "./pages/DiscoverPage";
import MovieDetailPage from "./pages/MovieDetailPage";
import FavoritesPage from "./pages/FavoritesPage";
import HistoryPage from "./pages/HistoryPage";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import ProtectedRoute from "./routes/ProtectedRoute"; // Pastikan jalurnya benar

export default function App() {
    return (
        <Routes>
            {/* 1. Rute Publik (Bisa diakses siapa saja) */}
            <Route path="/" element={<DiscoverPage />} />
            <Route path="/movies/:movieId" element={<MovieDetailPage />} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/register" element={<RegisterPage />} />

            {/* 2. Rute Terproteksi (Wajib Login) */}
            <Route element={<ProtectedRoute />}>
                <Route path="/favorites" element={<FavoritesPage />} />
                <Route path="/history" element={<HistoryPage />} />
            </Route>
        </Routes>
    );
}
