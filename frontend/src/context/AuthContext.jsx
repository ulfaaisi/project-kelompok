import { createContext, useEffect, useState } from "react";

export const AuthContext = createContext();

const API_URL = import.meta.env.VITE_API_URL;

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    async function getCsrfCookie() {
        await fetch(`${API_URL}/sanctum/csrf-cookie`, {
            credentials: "include",
        });
    }

    async function fetchUser() {
        try {
            const response = await fetch(`${API_URL}/api/auth/me`, {
                credentials: "include",
            });

            const result = await response.json();

            if (result.success) {
                setUser(result.data);
            }
        } catch {
            setUser(null);
        } finally {
            setLoading(false);
        }
    }

    async function login(email, password) {
        await getCsrfCookie();

        const response = await fetch(`${API_URL}/api/auth/login`, {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                email,
                password,
            }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Login gagal");
        }

        await fetchUser();
    }

    async function register(data) {
        await getCsrfCookie();

        const response = await fetch(`${API_URL}/api/auth/register`, {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Register gagal");
        }

        await login(data.email, data.password);
    }

    async function logout() {
        await fetch(`${API_URL}/api/auth/logout`, {
            method: "POST",
            credentials: "include",
            headers: {
                Accept: "application/json",
            },
        });

        setUser(null);
    }

    useEffect(() => {
        fetchUser();
    }, []);

    return (
        <AuthContext.Provider
            value={{
                user,
                loading,
                login,
                register,
                logout,
                fetchUser,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}
