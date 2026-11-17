<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use LogicException;

/**
 * Anggota perpustakaan. Nomor anggota tidak berubah, dan seorang anggota
 * tidak boleh meminjam lebih dari tiga koleksi sekaligus.
 * Anggota ke Peminjaman adalah komposisi: Peminjaman dibuat di dalam Anggota.
 */
final class Anggota
{
    public const BATAS_PINJAMAN = 3;

    /** @var Peminjaman[] */
    private array $riwayat = [];

    public function __construct(
        private readonly string $nomorAnggota,
        private readonly string $nama,
        private readonly JenisKeanggotaan $jenis,
    ) {
        if (trim($nomorAnggota) === '') {
            throw new InvalidArgumentException('Nomor anggota tidak boleh kosong.');
        }
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama anggota tidak boleh kosong.');
        }
    }

    public function jumlahPinjamanAktif(): int
    {
        $n = 0;
        foreach ($this->riwayat as $p) {
            if (!$p->sudahKembali()) {
                $n++;
            }
        }
        return $n;
    }

    public function bolehMeminjamLagi(): bool
    {
        return $this->jumlahPinjamanAktif() < self::BATAS_PINJAMAN;
    }

    /** Satu-satunya cara Peminjaman lahir. */
    public function catatPeminjaman(Koleksi $koleksi, CarbonImmutable $tanggalPinjam): Peminjaman
    {
        if (!$this->bolehMeminjamLagi()) {
            throw new LogicException($this->nama . ' sudah meminjam ' . self::BATAS_PINJAMAN
                . ' koleksi (batas maksimum).');
        }
        $p = new Peminjaman($this, $koleksi, $tanggalPinjam);
        $this->riwayat[] = $p;
        return $p;
    }

    /** @return Peminjaman[] salinan larik riwayat */
    public function getRiwayat(): array { return $this->riwayat; }

    public function getNomorAnggota(): string { return $this->nomorAnggota; }
    public function getNama(): string { return $this->nama; }
    public function getJenis(): JenisKeanggotaan { return $this->jenis; }
}
