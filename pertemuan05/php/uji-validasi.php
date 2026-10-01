<?php
declare(strict_types=1);

/**
 * Pembuktian checkpoint: konstruksi yang tidak sah ditolak saat objek dibuat.
 * Dipisah dari main.php supaya main.php tidak perlu disunting.
 */
require_once __DIR__ . '/BangunDatar.php';

function coba(string $keterangan, callable $pembuat): void
{
    try {
        $b = $pembuat();
        printf("  DITERIMA  %-26s -> luas = %.2f%s", $keterangan, $b->luas(), PHP_EOL);
    } catch (InvalidArgumentException $e) {
        printf("  DITOLAK   %-26s -> %s%s", $keterangan, $e->getMessage(), PHP_EOL);
    }
}

echo '=== Uji validasi constructor ===', PHP_EOL;
coba('Segitiga(3, 4, 5)',         fn () => new Segitiga(3, 4, 5));
coba('Segitiga(1, 2, 10)',        fn () => new Segitiga(1, 2, 10));
coba('Segitiga(1, 2, 3) (garis)', fn () => new Segitiga(1, 2, 3));
coba('Lingkaran(0)',              fn () => new Lingkaran(0));
coba('Persegi(-5)',               fn () => new Persegi(-5));
coba('Trapesium(4,10,6,5,5)',     fn () => new Trapesium(4, 10, 6, 5, 5));
