import { request } from "./client";

export async function getHistory() {
    return request("/api/history");
}
