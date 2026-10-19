<?php
declare(strict_types=1);

/** Tugas rumah 1 (Sistem Presensi Kuliah). Jalankan dari folder tugas-rumah/php:  php main.php */

spl_autoload_register(function (string $kelas): void {
    $awalan = 'Presensi\\';
    if (str_starts_with($kelas, $awalan)) {
        $berkas = __DIR__ . '/src/' . str_replace('\\', '/', substr($kelas, strlen($awalan))) . '.php';
        if (is_file($berkas)) {
            require $berkas;
        }
    }
});

use Presensi\Model\Dosen;
use Presensi\Model\KelasKuliah;
use Presensi\Model\Mahasiswa;
use Presensi\Model\MataKuliah;
use Presensi\Model\Pertemuan;
use Presensi\Model\StatusKehadiran as S;
use Presensi\Service\RekapPresensi;

function coba(string $keterangan, callable $aksi): void
{
    try {
        $aksi();
        echo "  MASALAH: seharusnya ditolak -> $keterangan", PHP_EOL;
    } catch (LogicException | InvalidArgumentException $e) {
        echo "  Ditolak ($keterangan): ", $e->getMessage(), PHP_EOL;
    }
}

$pbo = new MataKuliah('IF-203', 'Pemrograman Berorientasi Objek', 3);
$dosen = new Dosen('198501012010', 'Dr. Rina Wijaya');
$kelas = new KelasKuliah('IF-203-A', $pbo, $dosen);

echo '=== Agregasi: kelas menaungi mahasiswa ===', PHP_EOL;
$ani = new Mahasiswa('4525210001', 'Ani Lestari');
$budi = new Mahasiswa('4525210002', 'Budi Santoso');
$cici = new Mahasiswa('4525210003', 'Cici Hartono');
$kelas->daftarkan($ani);
$kelas->daftarkan($budi);
$kelas->daftarkan($cici);
echo '  ', $kelas, PHP_EOL;
$kelas->keluarkan($cici);
echo '  Setelah Cici keluar: ', $kelas, PHP_EOL;
echo '  Cici tetap ada sebagai objek: ', $cici, PHP_EOL;
$kelas->daftarkan($cici);

echo PHP_EOL, '=== Komposisi: pertemuan dan presensi lahir di dalam kelas ===', PHP_EOL;
$awal = new DateTimeImmutable('2026-09-07');
for ($i = 0; $i < 4; $i++) {
    $kelas->bukaPertemuan($awal->modify("+$i weeks"), 'Topik ' . ($i + 1));
}
$statusBudi = [S::Hadir, S::Izin, S::Hadir, S::Hadir];
$statusCici = [S::Hadir, S::Alpa, S::Alpa, S::Hadir];
for ($i = 1; $i <= 4; $i++) {
    $kelas->catatKehadiran($i, $ani, S::Hadir);
    $kelas->catatKehadiran($i, $budi, $statusBudi[$i - 1]);
    $kelas->catatKehadiran($i, $cici, $statusCici[$i - 1]);
}
foreach ($kelas->getPertemuan() as $p) {
    echo '  ', $p, PHP_EOL;
}

echo PHP_EOL, '  Mencoba mengubah koleksi internal dari luar...', PHP_EOL;
$salinan = $kelas->getPertemuan();
$salinan = [];
echo '    Larik pemanggil dikosongkan, pertemuan di dalam kelas masih: ', count($kelas->getPertemuan()), PHP_EOL;
try {
    $kelas->getPertemuan()[0]->getPresensi()[0]->status = S::Alpa;
} catch (Error $e) {
    echo '    Mengubah isi Presensi ditolak: ', $e->getMessage(), PHP_EOL;
}

echo PHP_EOL, '=== Rekap (dependensi) setelah 4 pertemuan ===', PHP_EOL;
$rekap = new RekapPresensi();
foreach ([$ani, $budi, $cici] as $m) {
    printf("  %-14s hadir %2d | izin %2d | alpa %2d | %5.1f%% | layak ujian: %s\n",
        $m->getNama(),
        $rekap->hitung($kelas, $m, S::Hadir),
        $rekap->hitung($kelas, $m, S::Izin),
        $rekap->hitung($kelas, $m, S::Alpa),
        $rekap->persentaseKehadiran($kelas, $m),
        $rekap->layakUjian($kelas, $m) ? 'ya' : 'tidak');
}

echo PHP_EOL, '=== Aturan bisnis ===', PHP_EOL;
coba('Mencatat dua kali pada pertemuan yang sama', fn () => $kelas->catatKehadiran(1, $ani, S::Hadir));
coba('Mencatat mahasiswa yang bukan peserta', fn () => $kelas->catatKehadiran(1, new Mahasiswa('4525219999', 'Orang Luar'), S::Hadir));
coba('Mencatat pertemuan yang belum dibuka', fn () => $kelas->catatKehadiran(9, $ani, S::Hadir));
for ($i = 5; $i <= 16; $i++) {
    $kelas->bukaPertemuan($awal->modify("+$i weeks"), "Topik $i");
}
coba('Membuka pertemuan ke-17', fn () => $kelas->bukaPertemuan($awal->modify('+17 weeks'), 'Topik 17'));
