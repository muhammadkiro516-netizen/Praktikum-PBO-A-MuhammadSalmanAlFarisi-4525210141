<?php
declare(strict_types=1);

require_once __DIR__ . '/Pegawai.php';

// Daftar polimorfik: satu array berisi empat jenis pegawai.
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

echo PHP_EOL, '=== Komposisi: profil pembayaran ===', PHP_EOL;
$ani = $daftar[0];
echo '  Sebelum: ', $ani->getProfilPembayaran()->bank, PHP_EOL;
$ani->setProfilPembayaran(new ProfilPembayaran('BNI', '1234567890'));
echo '  Sesudah: ', $ani->getProfilPembayaran()->bank, ' / ', $ani->getProfilPembayaran()->nomorRekening, PHP_EOL;
