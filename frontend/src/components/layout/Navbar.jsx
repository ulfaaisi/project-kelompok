import { Link, NavLink } from "react-router-dom";
import { useState } from "react";
import { useAuth } from "../../hooks/useAuth";

export default function Navbar() {
    const { user, logout } = useAuth();
    const [mobileOpen, setMobileOpen] = useState(false);

    const handleLogout = async () => {
        try {
            await logout();
        } catch (err) {
            console.error(err);
        }
    };

    return (
        <>
            <header className="navbar">
                <div className="navbar-container">
                    <Link to="/" className="logo">
                        CineMatch
                    </Link>

                    <nav className="nav-links">
                        <NavLink to="/">Discover</NavLink>
                        <NavLink to="/favorites">Favorit</NavLink>
                        <NavLink to="/history">Riwayat</NavLink>
                    </nav>

                    <div className="nav-user">
                        {user ? (
                            <>
                                <span>{user.name}</span>

                                <button onClick={handleLogout}>Keluar</button>
                            </>
                        ) : (
                            <Link to="/login">Masuk</Link>
                        )}

                        <button
                            className="mobile-menu-btn"
                            onClick={() => setMobileOpen(!mobileOpen)}
                        >
                            ☰
                        </button>
                    </div>
                </div>
            </header>

            {mobileOpen && (
                <div className="mobile-menu">
                    <NavLink to="/" onClick={() => setMobileOpen(false)}>
                        Discover
                    </NavLink>

                    <NavLink
                        to="/favorites"
                        onClick={() => setMobileOpen(false)}
                    >
                        Favorit
                    </NavLink>

                    <NavLink to="/history" onClick={() => setMobileOpen(false)}>
                        Riwayat
                    </NavLink>
                </div>
            )}
        </>
    );
}
