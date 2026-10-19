<?php
declare(strict_types=1);

namespace Presensi\Model;

use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;

/**
 * Satu kelas perkuliahan.
 *  - Pertemuan: KOMPOSISI (dibuat di dalam lewat bukaPertemuan, maksimum 16)
 *  - Mahasiswa: AGREGASI (peserta diterima dari luar lewat daftarkan)
 *  - MataKuliah dan Dosen: ASOSIASI (hanya dirujuk)
 */
class KelasKuliah
{
    public const MAKS_PERTEMUAN = 16;

    /** @var Pertemuan[] */
    private array $pertemuan = [];   // komposisi
    /** @var Mahasiswa[] */
    private array $peserta = [];     // agregasi

    public function __construct(
        private readonly string $kode,
        private readonly MataKuliah $mataKuliah,
        private readonly Dosen $dosen,
    ) {
        if (trim($kode) === '') {
            throw new InvalidArgumentException('Kode kelas wajib diisi');
        }
    }

    // ---- Agregasi ------------------------------------------------------------

    public function daftarkan(Mahasiswa $m): void
    {
        if (in_array($m, $this->peserta, true)) {
            throw new LogicException($m->getNama() . ' sudah terdaftar');
        }
        $this->peserta[] = $m;
    }

    /** Mahasiswa dilepas dari kelas, objeknya tetap hidup. */
    public function keluarkan(Mahasiswa $m): void
    {
        $this->peserta = array_values(array_filter($this->peserta, fn (Mahasiswa $x) => $x !== $m));
    }

    // ---- Komposisi -----------------------------------------------------------

    /** Pertemuan lahir DI DALAM kelas; pemanggil hanya menyerahkan tanggal dan topik. */
    public function bukaPertemuan(DateTimeImmutable $tanggal, string $topik): Pertemuan
    {
        if (count($this->pertemuan) >= self::MAKS_PERTEMUAN) {
            throw new LogicException('Satu kelas maksimum ' . self::MAKS_PERTEMUAN . ' pertemuan');
        }
        $p = new Pertemuan(count($this->pertemuan) + 1, $tanggal, $topik);
        $this->pertemuan[] = $p;
        return $p;
    }

    /** Hanya peserta kelas ini yang boleh dicatat kehadirannya. */
    public function catatKehadiran(int $nomorPertemuan, Mahasiswa $m, StatusKehadiran $status): void
    {
        if (!in_array($m, $this->peserta, true)) {
            throw new LogicException("Mahasiswa bukan peserta kelas {$this->kode}");
        }
        if ($nomorPertemuan < 1 || $nomorPertemuan > count($this->pertemuan)) {
            throw new InvalidArgumentException("Pertemuan $nomorPertemuan belum dibuka");
        }
        $this->pertemuan[$nomorPertemuan - 1]->catat($m, $status);
    }

    /** @return Pertemuan[] */
    public function getPertemuan(): array { return $this->pertemuan; }
    /** @return Mahasiswa[] */
    public function getPeserta(): array { return $this->peserta; }
    public function getKode(): string { return $this->kode; }
    public function getMataKuliah(): MataKuliah { return $this->mataKuliah; }
    public function getDosen(): Dosen { return $this->dosen; }

    public function __toString(): string
    {
        return sprintf('%s - %s | pengampu %s | %d peserta, %d pertemuan',
            $this->kode, $this->mataKuliah->getNama(), $this->dosen->getNama(),
            count($this->peserta), count($this->pertemuan));
    }
}
