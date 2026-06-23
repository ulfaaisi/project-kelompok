import { csrf, request } from "./client";

export async function login(payload) {
    await csrf();

    return request("/auth/login", {
        method: "POST",
        body: JSON.stringify(payload),
    });
}

export async function register(payload) {
    await csrf();

    return request("/auth/register", {
        method: "POST",
        body: JSON.stringify(payload),
    });
}

export async function logout() {
    return request("/auth/logout", {
        method: "POST",
    });
}

export async function me() {
    return request("/auth/me");
}
