import { request } from "./client";

export async function getFavorites() {
    return request("/api/favorites");
}

export async function addFavorite(payload) {
    return request("/api/favorites", {
        method: "POST",

        body: JSON.stringify(payload),
    });
}

export async function removeFavorite(movieId) {
    return request(`/api/favorites/${movieId}`, {
        method: "DELETE",
    });
}
