import { csrf, request } from "./client";

export async function login(payload) {
    await csrf();

    return request("/api/auth/login", {
        method: "POST",

        body: JSON.stringify(payload),
    });
}

export async function register(payload) {
    await csrf();

    return request("/api/auth/register", {
        method: "POST",

        body: JSON.stringify(payload),
    });
}

export async function logout() {
    return request("/api/auth/logout", {
        method: "POST",
    });
}

export async function me() {
    return request("/api/auth/me");
}
