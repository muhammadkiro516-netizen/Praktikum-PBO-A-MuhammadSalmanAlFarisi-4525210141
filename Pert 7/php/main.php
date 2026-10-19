<?php
declare(strict_types=1);

/**
 * Jalankan dari folder Pert 7/php:   php main.php
 */

spl_autoload_register(function (string $kelas): void {
    $awalan = 'Perpustakaan\\';
    if (str_starts_with($kelas, $awalan)) {
        $berkas = __DIR__ . '/src/' . str_replace('\\', '/', substr($kelas, strlen($awalan))) . '.php';
        if (is_file($berkas)) {
            require $berkas;
        }
    }
});

use Perpustakaan\Model\Anggota;
use Perpustakaan\Model\AnggotaKehormatan;
use Perpustakaan\Model\Buku;
use Perpustakaan\Model\Dosen;
use Perpustakaan\Model\JenisKeanggotaan;
use Perpustakaan\Model\Mahasiswa;
use Perpustakaan\Model\Majalah;
use Perpustakaan\Model\Skripsi;
use Perpustakaan\Service\PerpustakaanService;

function rupiah(int $jumlah): string
{
    return 'Rp' . number_format($jumlah, 0, ',', '.');
}

/** Menjalankan aksi yang HARUS ditolak dan mencetak alasannya. */
function tolak(string $keterangan, callable $aksi): void
{
    try {
        $aksi();
        echo "  MASALAH: seharusnya ditolak -> $keterangan", PHP_EOL;
    } catch (LogicException | InvalidArgumentException $e) {
        echo "  Ditolak ($keterangan): ", $e->getMessage(), PHP_EOL;
    }
}

$layanan = new PerpustakaanService();

// -------------------------------------------------------------------
echo '=== Komposisi: Buku memiliki Bab ===', PHP_EOL;
$pbo = new Buku('PBO-001', 'Pemrograman Berorientasi Objek', 'A. W. Pribadi', 2024, 3,
    ['Paradigma Objek', 'Enkapsulasi', 'Pewarisan dan Polimorfisme']);
echo '  ', $pbo, PHP_EOL;
foreach ($pbo->getBab() as $b) {
    echo '    ', $b, PHP_EOL;
}

echo PHP_EOL, '  Mencoba mengubah koleksi internal dari luar...', PHP_EOL;
$salinan = $pbo->getBab();
$salinan = [];   // mengosongkan salinan
echo '    Larik pemanggil dikosongkan, bab di dalam buku masih: ', count($pbo->getBab()), PHP_EOL;
try {
    $pbo->getBab()[0]->judul = 'Diubah dari luar';
} catch (Error $e) {
    echo '    Mengubah isi Bab ditolak: ', $e->getMessage(), PHP_EOL;
}

echo PHP_EOL, '  Multiplicity 1..*: buku tanpa bab ditolak constructor', PHP_EOL;
try {
    new Buku('PBO-X', 'Buku Kosong', 'Anonim', 2024, 1, []);
} catch (InvalidArgumentException $e) {
    echo '    Ditolak: ', $e->getMessage(), PHP_EOL;
}

// -------------------------------------------------------------------
echo PHP_EOL, '=== Agregasi: PerpustakaanService menaungi Koleksi dan Anggota ===', PHP_EOL;
$majalah = new Majalah('MJ-014', 'Majalah Informatika Edisi September', 'Redaksi', 2026, 2);
$skripsi = new Skripsi('SK-203', 'Sistem Informasi Perpustakaan', 'Dewi Kartika', 2025, 1);
$rusak = new Buku('PBO-002', 'Algoritma Dasar', 'B. Santoso', 2020, 1, ['Pengantar']);
$rusak->tandaiRusak();

$layanan->tambahKoleksi($pbo);
$layanan->tambahKoleksi($majalah);
$layanan->tambahKoleksi($skripsi);
$layanan->tambahKoleksi($rusak);
foreach ($layanan->getKoleksi() as $k) {
    echo '  ', $k, PHP_EOL;
}

$layanan->daftarkanAnggota(new Anggota('A-001', 'Ani Lestari', new Mahasiswa()));
$layanan->daftarkanAnggota(new Anggota('A-002', 'Budi Santoso', new Dosen()));
$layanan->daftarkanAnggota(new Anggota('A-003', 'Cici Hartono', new AnggotaKehormatan()));

echo PHP_EOL, '  Mengeluarkan skripsi dari katalog...', PHP_EOL;
$dilepas = $layanan->hapusKoleksi('SK-203');
echo '    Katalog sekarang berisi ', count($layanan->getKoleksi()), ' koleksi', PHP_EOL;
echo '    Objeknya tetap hidup di luar layanan: ', $dilepas->getJudul(), PHP_EOL;
$layanan->tambahKoleksi($dilepas);
echo '    Dimasukkan kembali, katalog berisi ', count($layanan->getKoleksi()), ' koleksi', PHP_EOL;

// -------------------------------------------------------------------
echo PHP_EOL, '=== Peminjaman dan aturan bisnis ===', PHP_EOL;
$hari0 = new DateTimeImmutable('2026-10-01');

$p1 = $layanan->pinjam('A-001', 'PBO-001', $hari0);
echo '  ', $p1, PHP_EOL;
echo '  Sisa eksemplar PBO-001: ', $pbo->getJumlahEksemplar(), PHP_EOL;

tolak('Skripsi tidak boleh dipinjam', fn () => $layanan->pinjam('A-001', 'SK-203', $hari0));
tolak('Koleksi rusak tidak boleh dipinjam', fn () => $layanan->pinjam('A-001', 'PBO-002', $hari0));

$layanan->pinjam('A-001', 'MJ-014', $hari0);
$layanan->pinjam('A-002', 'MJ-014', $hari0);
tolak('Majalah habis (2 eksemplar sudah dipinjam)', fn () => $layanan->pinjam('A-003', 'MJ-014', $hari0));

$layanan->pinjam('A-001', 'PBO-001', $hari0->modify('+1 day'));
tolak('Pinjaman keempat Ani melewati batas tiga', fn () => $layanan->pinjam('A-001', 'PBO-001', $hari0));

echo PHP_EOL, '  Sedang dipinjam:', PHP_EOL;
foreach ($layanan->daftarSedangDipinjam() as $p) {
    echo '    ', $p, PHP_EOL;
}

// -------------------------------------------------------------------
echo PHP_EOL, '=== Pengembalian dan denda ===', PHP_EOL;
$dendaAni = $layanan->kembalikan('A-001', 'PBO-001', new DateTimeImmutable('2026-10-20'));
echo '  Ani (Mahasiswa, Rp1.000/hari), 5 hari terlambat -> ', rupiah($dendaAni), PHP_EOL;
$dendaBudi = $layanan->kembalikan('A-002', 'MJ-014', new DateTimeImmutable('2026-10-14'));
echo '  Budi (Dosen, Rp500/hari), 10 hari terlambat -> ', rupiah($dendaBudi), PHP_EOL;

$layanan->pinjam('A-003', 'MJ-014', $hari0);
$dendaCici = $layanan->kembalikan('A-003', 'MJ-014', new DateTimeImmutable('2026-10-14'));
echo '  Cici (Kehormatan, bebas denda), 10 hari terlambat -> ', rupiah($dendaCici), PHP_EOL;

tolak('Mengembalikan koleksi yang tidak dipinjam', fn () => $layanan->kembalikan('A-002', 'PBO-001', $hari0));

echo PHP_EOL, '  Riwayat Ani:', PHP_EOL;
foreach ($layanan->riwayat('A-001') as $p) {
    echo '    ', $p, PHP_EOL;
}

// -------------------------------------------------------------------
echo PHP_EOL, '=== Jenis keanggotaan baru tanpa menyunting kelas lama ===', PHP_EOL;
$alumni = new class implements JenisKeanggotaan {
    public function nama(): string { return 'Alumni'; }
    public function dendaPerHari(): int { return 750; }
};
$layanan->daftarkanAnggota(new Anggota('A-004', 'Dedi Kurnia', $alumni));
$layanan->pinjam('A-004', 'MJ-014', $hari0);
$dendaDedi = $layanan->kembalikan('A-004', 'MJ-014', new DateTimeImmutable('2026-10-14'));
echo '  Dedi (Alumni, Rp750/hari), 10 hari terlambat -> ', rupiah($dendaDedi), PHP_EOL;
echo '  Tidak ada satu pun kelas lama yang disunting.', PHP_EOL;
