const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000"; // Fallback jika .env belum terbaca

async function csrf() {
    await fetch(`${API_URL}/sanctum/csrf-cookie`, {
        credentials: "include",
    });
}

async function request(endpoint, options = {}) {
    // Memastikan endpoint selalu diawali dengan tanda garis miring '/'
    const safeEndpoint = endpoint.startsWith("/") ? endpoint : `/${endpoint}`;

    const response = await fetch(`${API_URL}${safeEndpoint}`, {
        credentials: "include",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            ...options.headers,
        },
        ...options,
    });

    const contentType = response.headers.get("content-type");
    let data; // Cukup deklarasikan tanpa memberi nilai awal objek kosong {}

    // Mengisi variabel data berdasarkan tipe konten respons
    if (contentType && contentType.includes("application/json")) {
        data = await response.json();
    } else {
        // Jika server mengembalikan error HTML biasa (seperti 404 / 500)
        data = { message: `Server error dengan status ${response.status}` };
    }

    if (!response.ok) {
        throw data;
    }

    // SOLUSI BUG UNTUK PERTANYAAN SEBELUMNYA:
    // Jika data dari Laravel dibungkus di dalam properti 'data' (seperti result.data),
    // kita bongkar otomatis di sini agar frontend langsung menerima isi datanya.
    if (data && data.data !== undefined) {
        return data.data;
    }

    return data;
}

export { csrf, request };
