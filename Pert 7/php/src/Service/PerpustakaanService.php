<?php
declare(strict_types=1);

namespace Perpustakaan\Service;

use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;
use Perpustakaan\Model\Anggota;
use Perpustakaan\Model\Koleksi;
use Perpustakaan\Model\Peminjaman;

/**
 * Lapisan layanan Sistem Perpustakaan.
 *  - Koleksi dan Anggota: AGREGASI (diterima dari luar, tetap hidup bila dilepas).
 *  - Peminjaman: KOMPOSISI (hanya pinjam() yang melahirkannya).
 */
class PerpustakaanService
{
    /** Aturan bisnis: satu anggota maksimum tiga koleksi pada saat bersamaan. */
    public const BATAS_PINJAMAN_AKTIF = 3;

    /** @var Koleksi[] */
    private array $koleksi = [];     // agregasi
    /** @var Anggota[] */
    private array $anggota = [];     // agregasi
    /** @var Peminjaman[] */
    private array $peminjaman = [];  // komposisi

    // ---- Agregasi: objek bagian DATANG DARI LUAR ----------------------------

    public function tambahKoleksi(Koleksi $k): void
    {
        if ($this->cariKoleksi($k->getNomorInduk()) !== null) {
            throw new InvalidArgumentException('Nomor induk sudah terdaftar: ' . $k->getNomorInduk());
        }
        $this->koleksi[] = $k;
    }

    /** Mengeluarkan koleksi dari katalog dan MENGEMBALIKAN objeknya (tetap hidup). */
    public function hapusKoleksi(string $nomorInduk): Koleksi
    {
        $k = $this->ambilKoleksi($nomorInduk);
        foreach ($this->peminjaman as $p) {
            if (!$p->sudahKembali() && $p->getKoleksi() === $k) {
                throw new LogicException("Koleksi '{$k->getJudul()}' sedang dipinjam");
            }
        }
        $this->koleksi = array_values(array_filter($this->koleksi, fn (Koleksi $x) => $x !== $k));
        return $k;
    }

    public function daftarkanAnggota(Anggota $a): void
    {
        if ($this->cariAnggota($a->getNomorAnggota()) !== null) {
            throw new InvalidArgumentException('Nomor anggota sudah terdaftar: ' . $a->getNomorAnggota());
        }
        $this->anggota[] = $a;
    }

    // ---- Pencarian -----------------------------------------------------------

    public function cariKoleksi(string $nomorInduk): ?Koleksi
    {
        foreach ($this->koleksi as $k) {
            if ($k->getNomorInduk() === $nomorInduk) {
                return $k;
            }
        }
        return null;
    }

    /** @return Koleksi[] */
    public function cariJudul(string $kataKunci): array
    {
        return array_values(array_filter(
            $this->koleksi,
            fn (Koleksi $k) => str_contains(mb_strtolower($k->getJudul()), mb_strtolower($kataKunci))
        ));
    }

    public function cariAnggota(string $nomorAnggota): ?Anggota
    {
        foreach ($this->anggota as $a) {
            if ($a->getNomorAnggota() === $nomorAnggota) {
                return $a;
            }
        }
        return null;
    }

    // ---- Transaksi -----------------------------------------------------------

    public function pinjam(string $nomorAnggota, string $nomorInduk, ?DateTimeImmutable $tanggalPinjam = null): Peminjaman
    {
        $tanggalPinjam ??= new DateTimeImmutable('today');
        $a = $this->ambilAnggota($nomorAnggota);
        $k = $this->ambilKoleksi($nomorInduk);

        // Koleksi yang memutuskan dapat-tidaknya ia dipinjam (datanya ada padanya).
        if (!$k->dapatDipinjam()) {
            throw new LogicException("'{$k->getJudul()}' tidak dapat dipinjam: " . $k->alasanTidakDapatDipinjam());
        }
        // Batas tiga koleksi butuh data seluruh peminjaman, jadi dijaga di layanan.
        if ($this->jumlahPinjamanAktif($a) >= self::BATAS_PINJAMAN_AKTIF) {
            throw new LogicException($a->getNama() . ' sudah meminjam ' . self::BATAS_PINJAMAN_AKTIF . ' koleksi');
        }

        $k->kurangiStok();
        $p = new Peminjaman($a, $k, $tanggalPinjam);   // komposisi: lahir DI DALAM layanan
        $this->peminjaman[] = $p;
        return $p;
    }

    /** @return int denda dalam rupiah (0 bila tepat waktu) */
    public function kembalikan(string $nomorAnggota, string $nomorInduk, ?DateTimeImmutable $tanggalKembali = null): int
    {
        $tanggalKembali ??= new DateTimeImmutable('today');
        $a = $this->ambilAnggota($nomorAnggota);
        $k = $this->ambilKoleksi($nomorInduk);
        foreach ($this->peminjaman as $p) {
            if (!$p->sudahKembali() && $p->getAnggota() === $a && $p->getKoleksi() === $k) {
                $p->kembalikan($tanggalKembali);
                $k->tambahStok();
                return $p->hitungDenda($tanggalKembali);
            }
        }
        throw new LogicException($a->getNama() . " tidak sedang meminjam '{$k->getJudul()}'");
    }

    // ---- Laporan -------------------------------------------------------------

    /** @return Peminjaman[] daftar koleksi yang sedang dipinjam (kebutuhan petugas) */
    public function daftarSedangDipinjam(): array
    {
        return array_values(array_filter($this->peminjaman, fn (Peminjaman $p) => !$p->sudahKembali()));
    }

    /** @return Peminjaman[] riwayat seorang anggota (kebutuhan anggota) */
    public function riwayat(string $nomorAnggota): array
    {
        $a = $this->ambilAnggota($nomorAnggota);
        return array_values(array_filter($this->peminjaman, fn (Peminjaman $p) => $p->getAnggota() === $a));
    }

    /** @return Koleksi[] */
    public function getKoleksi(): array { return $this->koleksi; }
    /** @return Anggota[] */
    public function getAnggota(): array { return $this->anggota; }
    /** @return Peminjaman[] */
    public function getPeminjaman(): array { return $this->peminjaman; }

    // ---- Pembantu privat -----------------------------------------------------

    private function jumlahPinjamanAktif(Anggota $a): int
    {
        return count(array_filter(
            $this->peminjaman,
            fn (Peminjaman $p) => !$p->sudahKembali() && $p->getAnggota() === $a
        ));
    }

    private function ambilKoleksi(string $nomorInduk): Koleksi
    {
        return $this->cariKoleksi($nomorInduk)
            ?? throw new InvalidArgumentException("Koleksi tidak ditemukan: $nomorInduk");
    }

    private function ambilAnggota(string $nomorAnggota): Anggota
    {
        return $this->cariAnggota($nomorAnggota)
            ?? throw new InvalidArgumentException("Anggota tidak ditemukan: $nomorAnggota");
    }
}
