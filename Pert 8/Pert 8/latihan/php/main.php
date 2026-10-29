<?php
declare(strict_types=1);

require_once __DIR__ . '/koleksi.php';

/**
 * Checkpoint Langkah 6: satu fungsi memproses ketiga jenis koleksi tanpa
 * satu pun pemeriksaan tipe (tidak ada instanceof). Skripsi tidak perlu
 * dikecualikan dengan if, karena ia sendiri yang menjawab false.
 */
function ringkas(Peminjamable $p): string
{
    return $p->bolehDipinjam()
        ? 'boleh dipinjam ' . $p->masaPinjamHari() . ' hari'
        : 'tidak boleh dipinjam (baca di tempat)';
}

$daftar = [
    new Buku('B-001', 'Pemrograman Berorientasi Objek'),
    new Majalah('M-014', 'Majalah Informatika Edisi September'),
    new Skripsi('S-203', 'Sistem Informasi Perpustakaan'),
];

echo '=== Peminjamable ===', PHP_EOL;
foreach ($daftar as $p) {
    echo '  ', $p, '  -> ', ringkas($p), PHP_EOL;
}

echo PHP_EOL, '=== StatusPinjam ===', PHP_EOL;
foreach (StatusPinjam::cases() as $s) {
    printf('  %-10s %s%s', $s->name, $s->keterangan(), PHP_EOL);
}
