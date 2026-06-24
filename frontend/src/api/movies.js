import { request } from "./client"; // Menghubungkan ke helper client.js

// Mengambil list semua genre dari backend Laravel
export async function getGenres() {
    return request("/api/genres");
}

// Mengambil film rekomendasi acak berdasarkan filter
export async function getRecommendation(filters) {
    /* Mengubah filter { genre: "28", year: "2024", rating: "7" }
      menjadi string query: "genre=28&year=2024&rating=7"
    */
    const queryString = new URLSearchParams(filters).toString();

    // Dikirim menggunakan metode GET via Query String sesuai rute pencarian
    return request(`/api/movies/recommendation?${queryString}`);
}

// Mengambil detail lengkap satu film berdasarkan ID
export async function getMovie(movieId) {
    return request(`/api/movies/${movieId}`);
}
