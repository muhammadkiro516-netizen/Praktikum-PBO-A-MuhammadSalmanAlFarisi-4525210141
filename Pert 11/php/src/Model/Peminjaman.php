<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use LogicException;

/**
 * Satu catatan peminjaman. Tanggal jatuh tempo dihitung memakai pustaka
 * eksternal Carbon (tanggal pinjam + masa pinjam koleksi), bukan aritmetika manual.
 *
 * @internal Dibuat hanya oleh Anggota::catatPeminjaman().
 */
final class Peminjaman
{
    private const DETIK_PER_HARI = 86400;

    private readonly CarbonImmutable $tanggalJatuhTempo;
    private ?CarbonImmutable $tanggalKembali = null;

    public function __construct(
        private readonly Anggota $anggota,
        private readonly Koleksi $koleksi,
        private readonly CarbonImmutable $tanggalPinjam,
    ) {
        $this->tanggalJatuhTempo = $tanggalPinjam->addDays($koleksi->masaPinjamHari());
    }

    public function sudahKembali(): bool
    {
        return $this->tanggalKembali !== null;
    }

    public function kembalikan(CarbonImmutable $tanggal): void
    {
        if ($this->sudahKembali()) {
            throw new LogicException('Peminjaman ini sudah dikembalikan.');
        }
        if ($tanggal->getTimestamp() < $this->tanggalPinjam->getTimestamp()) {
            throw new InvalidArgumentException('Tanggal kembali tidak boleh sebelum tanggal pinjam.');
        }
        $this->tanggalKembali = $tanggal;
    }

    /** Jumlah hari terlambat; 0 bila tepat waktu atau belum dikembalikan. */
    public function hariTerlambat(): int
    {
        if ($this->tanggalKembali === null) {
            return 0;
        }
        $selisih = $this->tanggalKembali->getTimestamp() - $this->tanggalJatuhTempo->getTimestamp();
        return max(0, intdiv($selisih, self::DETIK_PER_HARI));
    }

    /** Denda = hari terlambat x denda per hari menurut jenis keanggotaan. */
    public function hitungDenda(): int
    {
        return $this->hariTerlambat() * $this->anggota->getJenis()->dendaPerHari();
    }

    public function getAnggota(): Anggota { return $this->anggota; }
    public function getKoleksi(): Koleksi { return $this->koleksi; }
    public function getTanggalPinjam(): CarbonImmutable { return $this->tanggalPinjam; }
    public function getTanggalJatuhTempo(): CarbonImmutable { return $this->tanggalJatuhTempo; }
    public function getTanggalKembali(): ?CarbonImmutable { return $this->tanggalKembali; }
}
