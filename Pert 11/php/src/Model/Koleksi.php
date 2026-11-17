<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use InvalidArgumentException;
use LogicException;

/**
 * Induk semua koleksi: buku, majalah, dan skripsi.
 * Data yang sama ditulis sekali di sini; masa pinjam dan boleh-tidaknya
 * dipinjam diserahkan ke turunan.
 */
abstract class Koleksi
{
    private StatusKoleksi $status = StatusKoleksi::Tersedia;

    public function __construct(
        private readonly string $nomorInduk,
        private readonly string $judul,
        private readonly string $penulis,
        private readonly int $tahun,
        private int $jumlahEksemplar,
    ) {
        if (trim($nomorInduk) === '') {
            throw new InvalidArgumentException('Nomor induk tidak boleh kosong.');
        }
        if (trim($judul) === '') {
            throw new InvalidArgumentException('Judul tidak boleh kosong.');
        }
        if ($tahun <= 0) {
            throw new InvalidArgumentException('Tahun terbit harus lebih besar dari 0.');
        }
        // Jumlah eksemplar tidak boleh negatif.
        if ($jumlahEksemplar < 0) {
            throw new InvalidArgumentException('Jumlah eksemplar tidak boleh negatif: ' . $jumlahEksemplar);
        }
    }

    /** Masa pinjam dalam hari. */
    abstract public function masaPinjamHari(): int;

    /** Apakah jenis koleksi ini boleh dipinjam sama sekali. */
    abstract public function bolehDipinjam(): bool;

    public function tersedia(): bool
    {
        return $this->status->bisaDipinjam() && $this->jumlahEksemplar > 0;
    }

    /** Kurangi stok; tolak bila sudah nol. */
    public function kurangiStok(): void
    {
        if ($this->jumlahEksemplar === 0) {
            throw new LogicException('Tidak ada eksemplar tersedia untuk ' . $this->nomorInduk . '.');
        }
        $this->jumlahEksemplar--;
        if ($this->jumlahEksemplar === 0 && $this->status === StatusKoleksi::Tersedia) {
            $this->status = StatusKoleksi::Dipinjam;
        }
    }

    public function tambahStok(): void
    {
        $this->jumlahEksemplar++;
        if ($this->status === StatusKoleksi::Dipinjam) {
            $this->status = StatusKoleksi::Tersedia;
        }
    }

    public function tandaiRusak(): void { $this->status = StatusKoleksi::Rusak; }
    public function tandaiHilang(): void { $this->status = StatusKoleksi::Hilang; }

    public function getNomorInduk(): string { return $this->nomorInduk; }
    public function getJudul(): string { return $this->judul; }
    public function getPenulis(): string { return $this->penulis; }
    public function getTahun(): int { return $this->tahun; }
    public function getJumlahEksemplar(): int { return $this->jumlahEksemplar; }
    public function getStatus(): StatusKoleksi { return $this->status; }
}
