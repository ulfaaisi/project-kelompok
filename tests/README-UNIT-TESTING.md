# Unit Testing CineMatch

Unit test difokuskan pada lapisan service dan binding Service Provider.

## Cakupan

- `AuthServiceTest`: register, login gagal, login berhasil, logout, dan current user.
- `FavoriteServiceTest`: daftar favorit, pencegahan duplikasi, tambah, serta hapus favorit.
- `GenreServiceTest`: penggunaan cache dan sinkronisasi genre TMDb.
- `HistoryServiceTest`: query berdasarkan user, limit, dan format hasil.
- `MovieServiceTest`: hasil kosong, format rekomendasi, penyimpanan riwayat, serta detail film.
- `AppServiceProviderTest`: memastikan lima contract terdaftar di Service Container.

## Menjalankan test

```bash
composer install
php artisan test --testsuite=Unit
```

atau:

```bash
vendor/bin/phpunit --testsuite=Unit
```

## Catatan sementara

Project masih memakai `app/contracts` dan file `AuthServiceIntrface.php`. Oleh karena itu `tests/bootstrap.php` memuat contract secara manual agar test dapat berjalan di Linux tanpa mengubah pekerjaan anggota lain.

Contract `App\\Contracts\\TMDb\\TMDbServiceInterface` juga belum tersedia. Unit test menggunakan stub di `tests/Support/TMDbServiceInterface.php`. Setelah contract asli dibuat, stub tersebut dapat dihapus.
