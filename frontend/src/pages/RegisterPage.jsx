import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";

export default function RegisterPage() {
    const { register } = useAuth();
    const navigate = useNavigate();

    const [form, setForm] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    const [error, setError] = useState("");

    async function handleSubmit(e) {
        e.preventDefault();

        try {
            setError("");

            // Mengirim objek form yang sudah rapi ke AuthContext
            await register(form);

            // Jika berhasil login otomatis, arahkan ke halaman utama/discover
            navigate("/");
        } catch (err) {
            console.error("Detail error dari Laravel:", err);

            // PERBAIKAN UTAMA: Ambil pesan error spesifik dari object validation Laravel 422
            if (err.errors) {
                // Menggabungkan semua pesan error validasi menjadi satu teks kalimat
                const errorMessages = Object.values(err.errors)
                    .flat()
                    .join(", ");
                setError(errorMessages);
            } else {
                // Fallback jika ada error tipe lain
                setError(err.message || "Registrasi gagal, silakan coba lagi.");
            }
        }
    }

    return (
        <div className="auth-page">
            <form className="auth-form" onSubmit={handleSubmit}>
                <h1>Daftar</h1>

                {/* Menampilkan pesan error validasi asli dari Laravel di komponen UI */}
                {error && (
                    <p
                        className="error-message"
                        style={{ color: "red", marginBottom: "1rem" }}
                    >
                        {error}
                    </p>
                )}

                <input
                    type="text"
                    placeholder="Nama"
                    value={form.name}
                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                    required
                />

                <input
                    type="email"
                    placeholder="Email"
                    value={form.email}
                    onChange={(e) =>
                        setForm({ ...form, email: e.target.value })
                    }
                    required
                />

                <input
                    type="password"
                    placeholder="Password"
                    value={form.password}
                    onChange={(e) =>
                        setForm({ ...form, password: e.target.value })
                    }
                    required
                />

                <input
                    type="password"
                    placeholder="Konfirmasi Password"
                    value={form.password_confirmation}
                    onChange={(e) =>
                        setForm({
                            ...form,
                            password_confirmation: e.target.value,
                        })
                    }
                    required
                />

                <button type="submit">Daftar</button>

                <p>
                    Sudah punya akun? <Link to="/login">Masuk</Link>
                </p>
            </form>
        </div>
    );
}
