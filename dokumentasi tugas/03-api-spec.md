# API Specification

> Dokumentasikan setiap endpoint yang dikembangkan maupun yang dikonsumsi dari layanan eksternal.
> Salin dan ulangi blok di bawah untuk setiap endpoint tambahan.

---

## 1- Register User

**Method:** `POST`

**URL:** `/api/auth/register`

**Deskripsi:** Mendaftarkan user baru dan mengembalikan Bearer Token untuk autentikasi.

**Autentikasi Diperlukan:** `Tidak`

**Sumber:** `Internal System`

**Request Headers:**
Content-Type: application/json

**Request Body:**

```json
{
  "name": "string",
  "email": "string",
  "password": "string",
  "password_confirmation": "string"
}
```

**Response Sukses (`201 Created`):**

```json
{
  "success": true,
  "message": "Registrasi berhasil.",
  "data": {
    "user": { "id": 1, "name": "John Doe", "email": "john@example.com" },
    "access_token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

**Response Gagal (`422 Unprocessable Entity`):**

```json
{
  "success": false,
  "message": "Validasi gagal.",
  "errors": { "email": ["Email sudah terdaftar."] }
}
```

---

## 2- Login User

**Method:** `POST`

**URL:** `/api/auth/login`

**Deskripsi:** Login dengan email dan password, mengembalikan Bearer Token baru (token lama dihapus).

**Autentikasi Diperlukan:** `Tidak`

**Sumber:** `Internal System`

**Request Headers:**
Content-Type: application/json

**Request Body:**

```json
{
  "email": "string",
  "password": "string"
}
```

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "user": { "id": 1, "name": "John Doe", "email": "john@example.com" },
    "access_token": "2|xyz789...",
    "token_type": "Bearer"
  }
}
```

**Response Gagal (`401 Unauthorized`):**

```json
{
  "success": false,
  "message": "Email atau password salah.",
  "data": null
}
```

---

## 3- Logout User

**Method:** `POST`

**URL:** `/api/auth/logout`

**Deskripsi:** Menghapus token aktif user (invalidasi sesi).

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**
Authorization: Bearer <token>
Content-Type: application/json

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Logout berhasil.",
  "data": null
}
```

**Response Gagal (`401 Unauthorized`):**

```json
{
  "success": false,
  "message": "Silakan login terlebih dahulu.",
  "data": null
}
```

---

## 4- Get Current User

**Method:** `GET`

**URL:** `/api/auth/me`

**Deskripsi:** Mengambil data user yang sedang login berdasarkan token.

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**
Authorization: Bearer <token>

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Data user berhasil diambil.",
  "data": { "id": 1, "name": "John Doe", "email": "john@example.com" }
}
```

---

## 5- Get All Genres

**Method:** `GET`

**URL:** `/api/genres`

**Deskripsi:** Mengambil daftar genre film yang disinkronisasi dari TMDb. Data di-cache 30 menit.

**Autentikasi Diperlukan:** `Tidak`

**Sumber:** `Third-Party API — TMDb`

**Request Headers:** `-`

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Genre berhasil diambil.",
  "data": [
    { "id": 1, "tmdb_genre_id": 28, "name": "Action" },
    { "id": 2, "tmdb_genre_id": 35, "name": "Comedy" }
  ]
}
```

---

## 6- Get Movie Recommendation

**Method:** `GET`

**URL:** `/api/recommendations`

**Deskripsi:** Mendapatkan satu rekomendasi film secara acak dari TMDb berdasarkan filter opsional. Otomatis menyimpan ke riwayat pencarian.

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Third-Party API — TMDb`

**Request Headers:**
Authorization: Bearer <token>

**Query Parameters:**
genre_id : integer (opsional) — tmdb_genre_id dari endpoint /genres
year : integer (opsional) — tahun rilis, min 1900
min_rating : numeric (opsional) — rating minimum 0–10

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Rekomendasi film berhasil ditemukan.",
  "data": {
    "id": 123456,
    "title": "Mission: Impossible - Dead Reckoning",
    "overview": "Ethan Hunt dan tim IMF...",
    "poster_url": "https://image.tmdb.org/t/p/w500/...",
    "backdrop_url": "https://image.tmdb.org/t/p/w1280/...",
    "release_date": "2023-07-12",
    "release_year": "2023",
    "rating": 7.6,
    "vote_count": 3421,
    "genre_ids": [28, 12, 53],
    "trailer_url": "https://www.youtube.com/watch?v=avz06PDqDbM",
    "trailer_available": true
  }
}
```

**Response Gagal (`404 Not Found`):**

```json
{
  "success": false,
  "message": "Tidak ada film yang ditemukan sesuai kriteria kamu. Coba ubah filter.",
  "data": null
}
```

---

## 7- Get Favorites

**Method:** `GET`

**URL:** `/api/favorites`

**Deskripsi:** Mengambil semua film favorit milik user yang sedang login, diurutkan terbaru.

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**
Authorization: Bearer <token>

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Daftar favorit berhasil diambil.",
  "data": [
    {
      "id": 1,
      "movie_id": 123456,
      "movie_title": "Mission: Impossible - Dead Reckoning",
      "poster_url": "https://image.tmdb.org/t/p/w500/poster.jpg",
      "release_year": "2023",
      "rating": 7.6,
      "saved_at": "2024-01-15T10:30:00+00:00"
    }
  ]
}
```

---

## 8- Add to Favorites

**Method:** `POST`

**URL:** `/api/favorites`

**Deskripsi:** Menambahkan film ke daftar favorit user. Mencegah duplikasi film yang sama.

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**
Authorization: Bearer <token>
Content-Type: application/json

**Request Body:**

```json
{
  "movie_id": 123456,
  "movie_title": "Mission: Impossible - Dead Reckoning",
  "poster_path": "/poster.jpg",
  "release_year": "2023",
  "rating": 7.6
}
```

**Response Sukses (`201 Created`):**

```json
{
  "success": true,
  "message": "Film berhasil ditambahkan ke favorit.",
  "data": {
    "id": 1,
    "movie_id": 123456,
    "movie_title": "Mission: Impossible - Dead Reckoning",
    "poster_url": "https://image.tmdb.org/t/p/w500/poster.jpg",
    "release_year": "2023",
    "rating": 7.6,
    "saved_at": "2024-01-15T10:30:00+00:00"
  }
}
```

**Response Gagal (`409 Conflict`):**

```json
{
  "success": false,
  "message": "Film ini sudah ada di daftar favorit kamu.",
  "data": null
}
```

---

## 9- Remove from Favorites

**Method:** `DELETE`

**URL:** `/api/favorites/{id}`

**Deskripsi:** Menghapus film dari favorit berdasarkan ID favorit. User hanya bisa menghapus favorit miliknya sendiri.

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**
Authorization: Bearer <token>

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Film berhasil dihapus dari favorit.",
  "data": null
}
```

**Response Gagal (`404 Not Found`):**

```json
{
  "success": false,
  "message": "Favorit tidak ditemukan atau bukan milik kamu.",
  "data": null
}
```

---

## 10- Get Search History

**Method:** `GET`

**URL:** `/api/history`

**Deskripsi:** Mengambil 50 riwayat pencarian rekomendasi terakhir milik user, beserta nama genre yang digunakan.

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**
Authorization: Bearer <token>

**Request Body:** `-`

**Response Sukses (`200 OK`):**

```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil diambil.",
  "data": [
    {
      "id": 5,
      "genre": { "id": 28, "name": "Action" },
      "year": "2023",
      "min_rating": 7.0,
      "searched_at": "2024-01-15T10:29:00+00:00"
    }
  ]
}
```
