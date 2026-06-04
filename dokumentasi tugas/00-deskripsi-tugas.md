# Deskripsi Tugas

## Laboratorium Pengembangan Perangkat Lunak

---

Proyek akhir ini merupakan tugas berbasis kelompok yang dirancang untuk mengukur kompetensi mahasiswa dalam merancang, mengembangkan, dan mendeploy sebuah aplikasi web yang bersifat modular dan berbasis layanan (_service-based architecture_). Setiap kelompok diwajibkan untuk membangun sistem yang terdiri dari minimal dua aplikasi terpisah yang saling berkomunikasi, mencerminkan praktik pengembangan perangkat lunak modern dalam lingkungan kolaboratif.

Setiap anggota kelompok akan ditetapkan pada satu peran (_role_) teknis yang spesifik sesuai dengan komposisi kelompok. Pembagian peran ini bertujuan untuk mensimulasikan alur kerja tim pengembang profesional, di mana setiap individu bertanggung jawab penuh atas domain teknis yang dipegang, sekaligus mampu berkoordinasi secara efektif dengan anggota tim lainnya.

Proyek yang dikerjakan wajib mengintegrasikan minimal satu _public API_ atau layanan pihak ketiga (_third-party API_) sebagai bagian dari fungsionalitas sistem. Penggunaan API eksternal ini dimaksudkan agar mahasiswa memperoleh pengalaman langsung dalam konsumsi layanan eksternal, pengelolaan autentikasi API, serta penanganan respons dan kesalahan (_error handling_) dari sumber data luar.

Sistem yang dibangun wajib memiliki minimal **lima fitur utama** yang terdokumentasi secara jelas, mencakup deskripsi fungsional, sumber data yang digunakan, serta role yang bertanggung jawab atas implementasinya.

---

## Ketentuan Umum

- Komposisi kelompok terdiri dari **3, 4, atau 5 orang**, dengan pembagian peran yang menyesuaikan jumlah anggota.
- Untuk kelompok **3 orang**: terdapat peran Frontend Developer, Backend Developer, dan DevOps Engineer.
- Untuk kelompok **4 orang**: terdapat peran Frontend Developer, Backend Developer, DevOps Engineer, dan Security Engineer.
- Untuk kelompok **5 orang**: terdapat **2 Frontend Developer**, **2 Backend Developer**, dan **1 DevOps Engineer**, dengan arsitektur **2 aplikasi frontend** dan **2 aplikasi backend** yang saling terintegrasi dalam satu ekosistem layanan.
- **Seluruh layanan backend wajib dibangun menggunakan framework Laravel.** Penggunaan framework backend lain tidak diperkenankan.
- Pilihan teknologi untuk frontend, database, infrastruktur, dan tooling lainnya diserahkan kepada masing-masing kelompok sesuai kebutuhan proyek.
- Setiap fitur wajib dicantumkan sumbernya secara eksplisit, apakah berasal dari sistem internal (_system_) atau dari layanan pihak ketiga (_third-party API_).
- Dokumentasi proyek wajib dikumpulkan bersamaan dengan _source code_ melalui repositori yang telah ditentukan.

---

## Role Pengembang

Pembagian peran dalam proyek ini mengikuti standar tim pengembangan perangkat lunak modern. Setiap role memiliki tanggung jawab yang berbeda namun saling bergantung satu sama lain.

### Frontend Developer

Frontend Developer bertanggung jawab atas seluruh aspek antarmuka pengguna (_user interface_) dan pengalaman pengguna (_user experience_) dari aplikasi. Peran ini mencakup implementasi tampilan visual, pengelolaan state aplikasi di sisi klien, serta komunikasi dengan layanan backend melalui API. Frontend Developer wajib memastikan aplikasi bersifat responsif, aksesibel, dan memiliki performa rendering yang optimal. Pada kelompok dengan 5 anggota, dua Frontend Developer membagi tanggung jawab berdasarkan aplikasi masing-masing — setiap Frontend Developer bertanggung jawab penuh atas satu aplikasi frontend.

### Backend Developer

Backend Developer bertanggung jawab atas logika bisnis aplikasi, pengelolaan basis data, serta penyediaan layanan API yang dikonsumsi oleh frontend. Seluruh layanan backend **wajib dibangun menggunakan Laravel**. Peran ini mencakup perancangan arsitektur layanan, implementasi autentikasi dan otorisasi, integrasi dengan _third-party API_, serta penanganan validasi dan kesalahan pada sisi server. Pada kelompok dengan 5 anggota, dua Backend Developer masing-masing bertanggung jawab penuh atas satu aplikasi backend yang berbeda domain atau layanannya.

### DevOps Engineer

DevOps Engineer bertanggung jawab atas infrastruktur, proses _deployment_, dan keberlangsungan operasional sistem. Peran ini mencakup konfigurasi lingkungan pengembangan dan produksi untuk **seluruh aplikasi** dalam ekosistem proyek, pengelolaan pipeline CI/CD, kontainerisasi aplikasi, serta pemantauan (_monitoring_) dan pencatatan log sistem. DevOps Engineer memastikan seluruh layanan — baik frontend maupun backend — dapat berjalan secara konsisten di berbagai lingkungan.

### Security Engineer _(khusus kelompok 4 orang)_

Security Engineer bertanggung jawab atas aspek keamanan sistem secara menyeluruh, mulai dari lapisan autentikasi, enkripsi data, hingga perlindungan terhadap celah keamanan umum seperti SQL Injection, XSS, dan CSRF. Peran ini bekerja lintas domain, berkolaborasi dengan Frontend, Backend, maupun DevOps untuk memastikan praktik keamanan diterapkan di setiap lapisan sistem. Security Engineer juga bertanggung jawab melakukan audit keamanan dasar sebelum pengumpulan proyek.