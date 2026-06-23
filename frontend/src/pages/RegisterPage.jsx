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

            await register(form);

            navigate("/");
        } catch (err) {
            setError(err.message);
        }
    }

    return (
        <div className="auth-page">
            <form className="auth-form" onSubmit={handleSubmit}>
                <h1>Daftar</h1>

                {error && <p>{error}</p>}

                <input
                    type="text"
                    placeholder="Nama"
                    value={form.name}
                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                />

                <input
                    type="email"
                    placeholder="Email"
                    value={form.email}
                    onChange={(e) =>
                        setForm({ ...form, email: e.target.value })
                    }
                />

                <input
                    type="password"
                    placeholder="Password"
                    value={form.password}
                    onChange={(e) =>
                        setForm({ ...form, password: e.target.value })
                    }
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
                />

                <button type="submit">Daftar</button>

                <p>
                    Sudah punya akun? <Link to="/login">Masuk</Link>
                </p>
            </form>
        </div>
    );
}
