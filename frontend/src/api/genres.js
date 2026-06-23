import { request } from "./client";

export function getGenres() {
    return request("/genres");
}
