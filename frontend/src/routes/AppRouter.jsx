import { BrowserRouter, Routes, Route } from "react-router-dom";

import DiscoverPage from "../pages/DiscoverPage";
import MovieDetailPage from "../pages/MovieDetailPage";
import FavoritesPage from "../pages/FavoritesPage";
import HistoryPage from "../pages/HistoryPage";
import LoginPage from "../pages/LoginPage";
import RegisterPage from "../pages/RegisterPage";

export default function AppRouter() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<DiscoverPage />} />
                <Route path="/movies/:movieId" element={<MovieDetailPage />} />
                <Route path="/favorites" element={<FavoritesPage />} />
                <Route path="/history" element={<HistoryPage />} />
                <Route path="/login" element={<LoginPage />} />
                <Route path="/register" element={<RegisterPage />} />
            </Routes>
        </BrowserRouter>
    );
}
