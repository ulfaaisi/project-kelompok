import { request } from "./client";

export function getHistory() {
    return request("/history");
}
