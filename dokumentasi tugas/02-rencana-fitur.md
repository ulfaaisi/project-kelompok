# Rencana Fitur

> Dokumentasikan minimal **5 fitur utama** proyek Anda.
> Salin dan ulangi blok di bawah untuk setiap fitur tambahan.

----

## Fitur 1 — Autentikasi User (Register, Login, Logout)

**Role Penanggung Jawab:** `Backend`

**Sumber Data:** `Internal System`

**Deskripsi & Ekspektasi:**
Fitur ini memungkinkan user untuk mendaftar akun baru, masuk ke sistem, dan keluar. Autentikasi menggunakan Laravel Sanctum berbasis token (Bearer Token). Saat register, sistem memvalidasi input (nama, email unik, password minimal 8 karakter dengan konfirmasi), membuat akun baru, dan langsung mengembalikan token akses. Saat login, sistem memverifikasi kredensial dan mengeluarkan token baru (token lama dihapus untuk single session). Endpoint yang dilindungi hanya dapat diakses jika request menyertakan header `Authorization: Bearer {token}`.

----

## Fitur 2 — Rekomendasi Film dari TMDb

**Role Penanggung Jawab:** `Backend`

**Sumber Data:** `Third-Party API — The Movie Database (TMDb)`

**Deskripsi & Ekspektasi:**
Fitur inti aplikasi. User dapat meminta satu rekomendasi film secara acak dengan mengirim filter opsional: genre, tahun rilis, dan rating minimum. Backend memanggil endpoint `GET /discover/movie` dari TMDb API, mengambil hasil dari beberapa halaman acak untuk meningkatkan variasi, lalu memilih satu film secara random. Sistem juga mengambil URL trailer YouTube dari TMDb. Seluruh hasil diformat konsisten melalui `MovieResource`. Jika tidak ada film yang cocok, API mengembalikan respons 404 dengan pesan yang jelas.

----

## Fitur 3 — Manajemen Film Favorit

**Role Penanggung Jawab:** `Backend`

**Sumber Data:** `Internal System`

**Deskripsi & Ekspektasi:**
User yang sudah login dapat menyimpan film yang disukai ke daftar favorit pribadi, melihat seluruh daftar favorit, dan menghapus film dari favorit. Data film (id, judul, poster, tahun, rating) disimpan di tabel `favorites` di database lokal — bukan setiap kali fetch ke TMDb. Sistem mencegah duplikasi: satu user tidak bisa menyimpan film yang sama dua kali (constraint unique). User hanya bisa menghapus favorit miliknya sendiri (authorization check by user_id).

----

## Fitur 4 — Riwayat Pencarian

**Role Penanggung Jawab:** `Backend`

**Sumber Data:** `Internal System`

**Deskripsi & Ekspektasi:**
Setiap kali user meminta rekomendasi film, sistem secara otomatis mencatat parameter pencarian (genre, tahun, rating minimum, dan waktu pencarian) ke tabel `search_histories`. User dapat melihat 50 riwayat pencarian terakhirnya melalui endpoint `GET /api/history`. Data riwayat menampilkan nama genre (bukan hanya ID), sehingga lebih mudah dibaca oleh frontend. Fitur ini berguna untuk menampilkan histori preferensi user.

----

## Fitur 5 — Sinkronisasi & Penyajian Genre Film

**Role Penanggung Jawab:** `Backend`

**Sumber Data:** `Third-Party API — The Movie Database (TMDb)`

**Deskripsi & Ekspektasi:**
Backend mengambil daftar genre film dari TMDb (`GET /genre/movie/list`) dan menyimpannya ke database lokal menggunakan `updateOrCreate` agar selalu sinkron. Data genre di-cache selama 30 menit menggunakan Laravel Cache untuk meminimalkan pemanggilan ke TMDb API. Endpoint `GET /api/genres` dapat diakses secara publik (tanpa login) sehingga frontend dapat menampilkan pilihan genre bahkan sebelum user masuk. Genre disimpan dengan `tmdb_genre_id` aslinya untuk digunakan sebagai filter rekomendasi.
