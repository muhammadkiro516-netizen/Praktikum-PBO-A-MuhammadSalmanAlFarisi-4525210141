<?php
declare(strict_types=1);

require_once __DIR__ . '/relasi.php';

echo '=== Komposisi: Buku memiliki Bab ===', PHP_EOL;
$buku = new Buku('PBO-001', 'Pemrograman Berorientasi Objek', 'A. W. Pribadi', 3);
$buku->tambahBab(1, 'Paradigma Objek');
$buku->tambahBab(2, 'Enkapsulasi');
$buku->tambahBab(3, 'Pewarisan dan Polimorfisme');
echo '  ', $buku, PHP_EOL;
foreach ($buku->getBab() as $b) {
    echo '    ', $b, PHP_EOL;
}

// Padanan percobaan bukti-relasi.md nomor 1: array dikembalikan by-value.
echo PHP_EOL, '  Mencoba mengosongkan larik bab dari luar...', PHP_EOL;
$salinan = $buku->getBab();
$salinan = [];
echo '    Larik salinan kini kosong, tetapi bab di dalam Buku tetap: ', count($buku->getBab()), PHP_EOL;

echo PHP_EOL, '=== Agregasi: Jurusan menaungi Mahasiswa ===', PHP_EOL;
$ani  = new Mahasiswa('2024001', 'Ani Lestari');
$budi = new Mahasiswa('2024002', 'Budi Santoso');

$informatika = new Jurusan('TI', 'Teknik Informatika');
$informatika->daftarkan($ani);
$informatika->daftarkan($budi);
echo '  ', $informatika, PHP_EOL;

$informatika->keluarkan($budi);
echo '  Setelah Budi pindah: ', $informatika, PHP_EOL;
echo '  Budi tetap ada sebagai objek: ', $budi, PHP_EOL;
echo PHP_EOL;
echo '  Itulah bedanya: Bab tidak bisa hidup di luar Buku,', PHP_EOL;
echo '  tetapi Mahasiswa tetap ada di luar Jurusan.', PHP_EOL;
