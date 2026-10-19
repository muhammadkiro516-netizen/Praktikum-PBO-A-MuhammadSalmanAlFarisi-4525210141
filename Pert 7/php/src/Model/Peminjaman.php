<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;

/**
 * Satu transaksi: satu anggota, satu koleksi. Keduanya hanya DIRUJUK (asosiasi)
 * dan tetap hidup tanpa Peminjaman ini.
 */
class Peminjaman
{
    private readonly DateTimeImmutable $tanggalJatuhTempo;
    private ?DateTimeImmutable $tanggalKembali = null;   // null selama belum dikembalikan

    public function __construct(
        private readonly Anggota $anggota,
        private readonly Koleksi $koleksi,
        private readonly DateTimeImmutable $tanggalPinjam,
    ) {
        // Jatuh tempo = tanggal pinjam + masa pinjam koleksi yang bersangkutan.
        $this->tanggalJatuhTempo = $tanggalPinjam->modify('+' . $koleksi->masaPinjamHari() . ' days');
    }

    public function sudahKembali(): bool
    {
        return $this->tanggalKembali !== null;
    }

    public function kembalikan(DateTimeImmutable $tanggal): void
    {
        if ($this->sudahKembali()) {
            throw new LogicException('Peminjaman ini sudah dikembalikan pada ' . $this->tanggalKembali->format('Y-m-d'));
        }
        if ($tanggal < $this->tanggalPinjam) {
            throw new InvalidArgumentException('Tanggal kembali tidak boleh sebelum tanggal pinjam');
        }
        $this->tanggalKembali = $tanggal;
    }

    /** Hari terlambat sampai tanggal acuan (hari ini bila belum kembali). Tidak pernah negatif. */
    public function hariTerlambat(DateTimeImmutable $tanggalAcuan): int
    {
        $akhir = $this->tanggalKembali ?? $tanggalAcuan;
        $selisih = (int) $this->tanggalJatuhTempo->diff($akhir)->format('%r%a');
        return max(0, $selisih);
    }

    /** Denda = hari terlambat x denda per hari milik jenis keanggotaan (dependensi). */
    public function hitungDenda(DateTimeImmutable $tanggalAcuan): int
    {
        return $this->hariTerlambat($tanggalAcuan) * $this->anggota->getJenis()->dendaPerHari();
    }

    public function getAnggota(): Anggota { return $this->anggota; }
    public function getKoleksi(): Koleksi { return $this->koleksi; }
    public function getTanggalPinjam(): DateTimeImmutable { return $this->tanggalPinjam; }
    public function getTanggalJatuhTempo(): DateTimeImmutable { return $this->tanggalJatuhTempo; }
    public function getTanggalKembali(): ?DateTimeImmutable { return $this->tanggalKembali; }

    public function __toString(): string
    {
        return sprintf("%s meminjam '%s' | pinjam %s, tempo %s, kembali %s",
            $this->anggota->getNama(), $this->koleksi->getJudul(),
            $this->tanggalPinjam->format('Y-m-d'), $this->tanggalJatuhTempo->format('Y-m-d'),
            $this->tanggalKembali?->format('Y-m-d') ?? '-');
    }
}
