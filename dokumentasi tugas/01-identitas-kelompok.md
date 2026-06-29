# Identitas Kelompok

---

**Nama Kelompok:** `ThreeGirls`

**Nama Proyek / Aplikasi:** `CineMatch`

**Jumlah Anggota:** `3` orang

**Repositori:** `https://github.com/ulfaaisi/project-kelompok`
**link deploy:** `https://positive-caring-production-3d04.up.railway.app/`

---

## Anggota & Role

**Anggota 1**

- Nama Lengkap: `Uswatul Hasanah`
- NIM: `230705033`
- Role: `Frontend Developer`
- Teknologi: `React.js (Vite), Tailwind CSS, Laravel Http, React Router DOM, Context API`

**Anggota 2**

- Nama Lengkap: `Naila Huwaida Nisa`
- NIM: `230705018`
- Role: `Backend Developer`
- Teknologi: `Laravel 13, PHP 8.4+, MySQL, Laravel Sanctum, TMDb API, L5-Swagger`

**Anggota 3**

- Nama Lengkap: `Ulfa Rahadatul Aisy`
- NIM: `230705012`
- Role: `DevOps Engineer `
- Teknologi: `Docker, Docker Compose, GitHub Actions (CI/CD), Nginx, Railway / VPS, Log Viewer`

---

## Stack Teknologi

**Frontend:** `React.js & Tailwind CSS`

**Backend:** `Laravel` _(wajib)_
_(PHP 8.2+, MySQL, REST API, Laravel Sanctum untuk autentikasi token)_

**Database:** `mysql`

**DevOps / Infrastruktur:** `Docker`

---

## Arsitektur Aplikasi

_(Jelaskan secara singkat bagaimana aplikasi-aplikasi dalam proyek ini saling terhubung)_

**Aplikasi 1 — Frontend**

- Nama Aplikasi: `CineMatch Web Client`
- Deskripsi Singkat: `Aplikasi web (CineMatch) yang dibuat menggunakan React dan Tailwind CSS. Aplikasi ini berfungsi sebagai wadah bagi pengguna untuk mencari rekomendasi film acak berdasarkan pilihan genre atau tahun, menonton trailer, melihat galeri foto adegan film, serta menyimpan daftar film favorit dan melihat riwayat pencarian mereka setelah login.`
- Berkomunikasi dengan: `Aplikasi 2 — Backend (Movie Recommendation) melalui HTTP Fetch/Axios dengan menyertakan Bearer Token`

**Aplikasi 2 — Backend (Laravel)**

- Nama Aplikasi / Service: `Movie Recommendation`
- Deskripsi Singkat: `REST API yang menyediakan fitur autentikasi user, rekomendasi film acak dari TMDb berdasarkan filter genre/tahun/rating, manajemen favorit, dan riwayat pencarian.`
- Menyediakan layanan untuk: `Aplikasi Frontend (dikonsumsi via HTTP dengan Bearer Token)`

