import { createContext, useEffect, useState } from "react";

import { csrf, request } from "../api/client";

export const AuthContext = createContext();

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);

    const [loading, setLoading] = useState(true);

    async function fetchUser() {
        try {
            const user = await request("/api/auth/me");

            setUser(user);
        } catch {
            setUser(null);
        } finally {
            setLoading(false);
        }
    }

    async function login(email, password) {
        await csrf();

        await request("/api/auth/login", {
            method: "POST",
            body: JSON.stringify({
                email,
                password,
            }),
        });

        await fetchUser();
    }

    async function register(payload) {
        await csrf();

        await request("/api/auth/register", {
            method: "POST",
            body: JSON.stringify(payload),
        });

        await fetchUser();
    }

    async function logout() {
        await request("/api/auth/logout", {
            method: "POST",
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
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}
