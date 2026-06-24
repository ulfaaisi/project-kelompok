import { Navigate, Outlet } from "react-router-dom";

import { useAuth } from "../hooks/useAuth";

export default function ProtectedRoute() {
    const { user, loading } = useAuth();

    if (loading) {
        return <div className="page-loader">Loading...</div>;
    }

    return user ? <Outlet /> : <Navigate to="/login" replace />;
}
