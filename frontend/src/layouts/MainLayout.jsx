import Navbar from "../components/layout/Navbar";

export default function MainLayout({ children }) {
    return (
        <div className="app-shell">
            <Navbar />

            <main className="main-content">{children}</main>
        </div>
    );
}
