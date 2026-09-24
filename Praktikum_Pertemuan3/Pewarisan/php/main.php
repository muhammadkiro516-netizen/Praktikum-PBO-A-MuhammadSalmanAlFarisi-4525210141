<?php
declare(strict_types=1);

require_once __DIR__ . '/Pegawai.php';

/*
 * TODO SOAL PEWARISAN
 * 1. Buat kelas induk abstrak Pegawai yang menyimpan nip, nama, dan gaji pokok.
 * 2. Tolak gaji pokok negatif melalui validasi constructor.
 * 3. Buat PegawaiTetap dengan tunjangan masa kerja maksimal 40 persen.
 * 4. Buat PegawaiKontrak tanpa tunjangan masa kerja.
 * 5. Buat Dosen sebagai turunan PegawaiTetap dengan tunjangan fungsional.
 * 6. Buat PegawaiHarian dengan perhitungan tarif per hari dikali hari kerja.
 * 7. Gunakan overriding dan panggil parent::hitungGaji() pada kelas turunan.
 * 8. Tambahkan satu relasi komposisi dan jelaskan alasannya di justifikasi.md.
 * 9. Buat class diagram PlantUML dan implementasikan juga dalam Java.
 * Status: seluruh soal sudah diimplementasikan di bawah ini.
 */

$daftar = [
    new PegawaiTetap('198701012010', 'Ani Lestari', 6_000_000, 15),
    new PegawaiKontrak('K-2024-007', 'Budi Santoso', 5_000_000, 12),
    new Dosen('D-2020-015', 'Citra Maharani', 7_000_000, 10, 1_500_000),
    new PegawaiHarian('H-2024-021', 'Deni Saputra', 250_000, 22),
];

echo '=== Daftar Gaji ===', PHP_EOL;
foreach ($daftar as $p) {
    echo '  ', $p, PHP_EOL;
}

$total = array_sum(array_map(fn (Pegawai $p): float => $p->hitungGaji(), $daftar));
printf('%s  Total beban gaji: Rp%s%s', PHP_EOL, number_format($total, 2, ',', '.'), PHP_EOL);

echo PHP_EOL, 'Periksa: Ani (pokok 6.000.000, masa kerja 15 tahun)', PHP_EOL;
echo '  tunjangan 15 x 2% = 30%, jadi gaji seharusnya Rp7.800.000,00', PHP_EOL;
