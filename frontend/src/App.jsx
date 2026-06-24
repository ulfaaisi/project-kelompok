import { Routes, Route } from "react-router-dom";
import { Toaster } from "react-hot-toast";

import DiscoverPage from "./pages/DiscoverPage";
import MovieDetailPage from "./pages/MovieDetailPage";
import FavoritesPage from "./pages/FavoritesPage";
import HistoryPage from "./pages/HistoryPage";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";

import ProtectedRoute from "./routes/ProtectedRoute";

export default function App() {
    return (
        <>
            <Toaster position="top-right" />

            <Routes>
                {/* Public Routes */}
                <Route path="/" element={<DiscoverPage />} />
                <Route path="/movies/:movieId" element={<MovieDetailPage />} />
                <Route path="/login" element={<LoginPage />} />
                <Route path="/register" element={<RegisterPage />} />

                {/* Protected Routes */}
                <Route element={<ProtectedRoute />}>
                    <Route path="/favorites" element={<FavoritesPage />} />
                    <Route path="/history" element={<HistoryPage />} />
                </Route>
            </Routes>
        </>
    );
}
