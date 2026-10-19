<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use InvalidArgumentException;
use LogicException;

/**
 * Induk semua koleksi. Aturan yang berbeda antarjenis (masa pinjam dan
 * boleh-tidaknya dipinjam) dibiarkan abstrak.
 */
abstract class Koleksi
{
    private int $jumlahEksemplar;
    private StatusKoleksi $status = StatusKoleksi::Tersedia;

    public function __construct(
        private readonly string $nomorInduk,
        private readonly string $judul,
        private readonly string $penulis,
        private readonly int $tahun,
        int $jumlahEksemplar,
    ) {
        if (trim($nomorInduk) === '') {
            throw new InvalidArgumentException('Nomor induk tidak boleh kosong');
        }
        if (trim($judul) === '') {
            throw new InvalidArgumentException('Judul tidak boleh kosong');
        }
        if (trim($penulis) === '') {
            throw new InvalidArgumentException('Penulis tidak boleh kosong');
        }
        if ($tahun <= 0) {
            throw new InvalidArgumentException("Tahun terbit harus positif: $tahun");
        }
        if ($jumlahEksemplar < 0) {
            throw new InvalidArgumentException("Jumlah eksemplar tidak boleh negatif: $jumlahEksemplar");
        }
        $this->jumlahEksemplar = $jumlahEksemplar;
        if ($jumlahEksemplar === 0) {
            $this->status = StatusKoleksi::Dipinjam;
        }
    }

    /** Masa pinjam dalam hari; tiap jenis koleksi punya aturannya sendiri. */
    abstract public function masaPinjamHari(): int;

    /** Apakah JENIS koleksi ini pada dasarnya boleh dipinjam (skripsi: tidak). */
    abstract public function bolehDipinjam(): bool;

    public function tersedia(): bool
    {
        return $this->jumlahEksemplar > 0;
    }

    /** Koleksi sendiri yang memutuskan dapat-tidaknya ia dipinjam. */
    public function dapatDipinjam(): bool
    {
        return $this->bolehDipinjam() && $this->status->membolehkanPeminjaman() && $this->tersedia();
    }

    /** Alasan penolakan untuk pesan galat; null bila koleksi dapat dipinjam. */
    public function alasanTidakDapatDipinjam(): ?string
    {
        if (!$this->bolehDipinjam()) {
            return 'hanya boleh dibaca di tempat';
        }
        if (!$this->status->membolehkanPeminjaman()) {
            return 'berstatus ' . strtolower($this->status->label());
        }
        if (!$this->tersedia()) {
            return 'tidak ada eksemplar tersedia';
        }
        return null;
    }

    public function kurangiStok(): void
    {
        if ($this->jumlahEksemplar === 0) {
            throw new LogicException("Eksemplar '{$this->judul}' sudah habis");
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

    public function tandaiRusak(): void
    {
        $this->status = StatusKoleksi::Rusak;
    }

    public function tandaiHilang(): void
    {
        $this->status = StatusKoleksi::Hilang;
    }

    public function getNomorInduk(): string { return $this->nomorInduk; }
    public function getJudul(): string { return $this->judul; }
    public function getPenulis(): string { return $this->penulis; }
    public function getTahun(): int { return $this->tahun; }
    public function getJumlahEksemplar(): int { return $this->jumlahEksemplar; }
    public function getStatus(): StatusKoleksi { return $this->status; }

    public function __toString(): string
    {
        return sprintf('%-8s %-38s %-14s %d [%d eks, %s]',
            $this->nomorInduk, $this->judul, $this->penulis, $this->tahun,
            $this->jumlahEksemplar, $this->status->label());
    }
}
