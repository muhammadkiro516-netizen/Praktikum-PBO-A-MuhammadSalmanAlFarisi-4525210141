<?php
declare(strict_types=1);

// Satu-satunya pemanggilan berkas: autoload Composer. Pemanggilan manual per kelas sudah dihapus.
require __DIR__ . '/vendor/autoload.php';

use Carbon\CarbonImmutable;
use Perpustakaan\Model\Anggota;
use Perpustakaan\Model\Buku;
use Perpustakaan\Model\KeanggotaanDosen;
use Perpustakaan\Model\KeanggotaanKehormatan;
use Perpustakaan\Model\KeanggotaanMahasiswa;
use Perpustakaan\Model\Majalah;
use Perpustakaan\Model\Peminjaman;
use Perpustakaan\Model\Skripsi;
use Perpustakaan\Service\PerpustakaanService;
use Perpustakaan\Util\Format;

date_default_timezone_set('Asia/Jakarta');

function cetakTabel(PerpustakaanService $layanan): void
{
    echo str_pad('Nomor', 9), str_pad('Judul', 33), str_pad('Jenis', 9),
        str_pad('Eks', 3, ' ', STR_PAD_LEFT), '  Status', PHP_EOL;
    echo str_repeat('-', 66), PHP_EOL;
    foreach ($layanan->daftarKoleksi() as $k) {
        echo str_pad($k->getNomorInduk(), 9),
            str_pad($k->getJudul(), 33),
            str_pad((new ReflectionClass($k))->getShortName(), 9),
            str_pad((string) $k->getJumlahEksemplar(), 3, ' ', STR_PAD_LEFT),
            '  ', $k->getStatus()->label(), PHP_EOL;
    }
}

function cetakPinjam(Peminjaman $p): void
{
    echo '  ', $p->getAnggota()->getNama(), ' meminjam ', $p->getKoleksi()->getJudul(),
        ', jatuh tempo ', Format::tanggal($p->getTanggalJatuhTempo()), PHP_EOL;
}

function cetakKembali(Peminjaman $p): void
{
    echo '  ', $p->getAnggota()->getNama(), ' (', $p->getAnggota()->getJenis()->nama(), ') ',
        'mengembalikan ', $p->getKoleksi()->getJudul(), ': terlambat ', $p->hariTerlambat(),
        ' hari, denda ', Format::rupiah($p->hitungDenda()), PHP_EOL;
}

function coba(string $keterangan, callable $aksi): void
{
    try {
        $aksi();
        echo '  MASALAH: "', $keterangan, '" seharusnya ditolak.', PHP_EOL;
    } catch (LogicException | InvalidArgumentException $e) {
        echo '  Ditolak (', $keterangan, '): ', $e->getMessage(), PHP_EOL;
    }
}

$layanan = new PerpustakaanService();

$pbo = new Buku('PBO-001', 'Pemrograman Berorientasi Objek', 'A. W. Pribadi', 2020, 3);
$pbo->tambahBab(1, 'Paradigma Objek');
$pbo->tambahBab(2, 'Enkapsulasi');
$pbo->tambahBab(3, 'Pewarisan dan Polimorfisme');
$layanan->tambahKoleksi($pbo);
$layanan->tambahKoleksi(new Buku('BD-001', 'Sistem Basis Data', 'R. Elmasri', 2016, 2));
$layanan->tambahKoleksi(new Majalah('MJ-001', 'Majalah Teknologi', 'Redaksi', 2026, 1));
$layanan->tambahKoleksi(new Skripsi('SK-001', 'Analisis Sistem Informasi', 'Mahasiswa TI', 2025, 1));

$layanan->daftarkanAnggota(new Anggota('A001', 'Ani Lestari', new KeanggotaanMahasiswa()));
$layanan->daftarkanAnggota(new Anggota('D001', 'Dr. Rina Pratama', new KeanggotaanDosen()));
$layanan->daftarkanAnggota(new Anggota('K001', 'Pak Hasan', new KeanggotaanKehormatan()));

echo '=== Daftar koleksi ===', PHP_EOL;
cetakTabel($layanan);

// Tanggal dikelola oleh pustaka eksternal Carbon.
$awal = CarbonImmutable::parse('2026-10-01')->startOfDay();
echo PHP_EOL, '=== Peminjaman (', Format::tanggal($awal), ') ===', PHP_EOL;
cetakPinjam($layanan->pinjam('A001', 'PBO-001', $awal));
cetakPinjam($layanan->pinjam('A001', 'MJ-001', $awal));
cetakPinjam($layanan->pinjam('D001', 'BD-001', $awal));
$layanan->pinjam('A001', 'BD-001', $awal);        // pinjaman ke-3 Ani

echo PHP_EOL, '=== Aturan yang harus menolak ===', PHP_EOL;
coba('Pinjam skripsi', fn() => $layanan->pinjam('A001', 'SK-001', $awal));
coba('Majalah sudah habis', fn() => $layanan->pinjam('K001', 'MJ-001', $awal));
coba('Pinjaman ke-4 Ani', fn() => $layanan->pinjam('A001', 'PBO-001', $awal));

$kembali = CarbonImmutable::parse('2026-10-20')->startOfDay();
echo PHP_EOL, '=== Pengembalian terlambat (', Format::tanggal($kembali), ') ===', PHP_EOL;
cetakKembali($layanan->kembalikan('A001', 'PBO-001', $kembali));
cetakKembali($layanan->kembalikan('D001', 'BD-001', $kembali));
$layanan->pinjam('K001', 'PBO-001', $awal);
cetakKembali($layanan->kembalikan('K001', 'PBO-001', $kembali));

echo PHP_EOL, '=== Daftar koleksi setelah transaksi ===', PHP_EOL;
cetakTabel($layanan);

echo PHP_EOL, '=== Sedang dipinjam ===', PHP_EOL;
foreach ($layanan->daftarSedangDipinjam() as $p) {
    echo '  ', $p->getAnggota()->getNama(), ' -> ', $p->getKoleksi()->getJudul(),
        ' (jatuh tempo ', Format::tanggal($p->getTanggalJatuhTempo()), ')', PHP_EOL;
}
