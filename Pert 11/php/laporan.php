<?php
declare(strict_types=1);

// Tiga laporan fungsional (padanan LaporanApp.java). Jalankan: php laporan.php
require __DIR__ . '/vendor/autoload.php';

use Perpustakaan\Model\Buku;
use Perpustakaan\Model\KoleksiBuku;
use Perpustakaan\Service\LaporanBuku;
use Perpustakaan\Service\LibraryService;

$layanan = new LibraryService();
$layanan->tambahBuku(new Buku('978-1', 'Basis Data', 'R. Elmasri', 2016, 2));
$layanan->tambahBuku(new Buku('978-2', 'Pemrograman Berorientasi Objek', 'A. W. Pribadi', 2020, 3));
$layanan->tambahBuku(new Buku('978-3', 'Jaringan Komputer', 'A. Tanenbaum', 2011, 0));
$layanan->tambahBuku(new Buku('978-4', 'Algoritma dan Struktur Data', 'A. W. Pribadi', 2019, 1));
$layanan->tambahBuku(new Buku('978-5', 'Sistem Operasi', 'A. Silberschatz', 2018, 2));
$semua = new KoleksiBuku($layanan->semuaBuku());

echo '=== Laporan 1: buku yang tersedia ===', PHP_EOL;
foreach (LaporanBuku::tersedia($semua) as $b) {
    echo '  ', $b->getNomorInduk(), '  ', $b->getJudul(), '  (', $b->getJumlahEksemplar(), ' eks)', PHP_EOL;
}

echo PHP_EOL, '=== Laporan 2: seluruh judul ===', PHP_EOL;
echo '  ', LaporanBuku::judulGabung($semua, '; '), PHP_EOL;

echo PHP_EOL, '=== Laporan 3: jumlah buku per penulis ===', PHP_EOL;
foreach (LaporanBuku::jumlahPerPenulis($semua) as $penulis => $n) {
    echo '  ', $penulis, ': ', $n, PHP_EOL;
}

echo PHP_EOL, '=== Optional: pencarian yang aman ===', PHP_EOL;
echo '  978-2 -> ', $layanan->cariBuku('978-2')?->getJudul() ?? '(tidak ada)', PHP_EOL;
echo '  999   -> ', $layanan->cariBuku('999')?->getJudul() ?? '(tidak ada)', PHP_EOL;
