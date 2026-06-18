<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

/*
 * Project sementara masih menggunakan folder app/contracts (huruf kecil)
 * dan nama file AuthServiceIntrface.php. File contract dimuat manual agar
 * unit test tetap dapat dijalankan di Linux tanpa mengubah tugas anggota lain.
 */
$contractFiles = [
    'AuthServiceIntrface.php',
    'FavoriteServiceInterface.php',
    'GenreServiceInterface.php',
    'HistoryServiceInterface.php',
    'MovieServiceInterface.php',
];

foreach ($contractFiles as $contractFile) {
    $path = dirname(__DIR__).'/app/contracts/'.$contractFile;

    if (is_file($path)) {
        require_once $path;
    }
}

if (! interface_exists(\App\Contracts\TMDb\TMDbServiceInterface::class)) {
    require_once __DIR__.'/Support/TMDbServiceInterface.php';
}
