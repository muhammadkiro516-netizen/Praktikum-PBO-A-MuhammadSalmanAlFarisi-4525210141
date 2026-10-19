<?php
declare(strict_types=1);

namespace Presensi\Model;

use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;

/**
 * KOMPOSISI: Pertemuan memiliki 0..* Presensi, dan Pertemuan sendiri bagian dari KelasKuliah.
 * @internal Hanya KelasKuliah::bukaPertemuan() yang boleh membuat Pertemuan.
 */
class Pertemuan
{
    /** @var Presensi[] */
    private array $presensi = [];

    public function __construct(
        private readonly int $nomor,
        private readonly DateTimeImmutable $tanggal,
        private readonly string $topik,
    ) {
        if ($nomor <= 0) {
            throw new InvalidArgumentException("Nomor pertemuan harus positif: $nomor");
        }
        if (trim($topik) === '') {
            throw new InvalidArgumentException('Topik wajib diisi');
        }
    }

    /** Satu-satunya cara Presensi lahir. Satu mahasiswa hanya satu catatan per pertemuan. */
    public function catat(Mahasiswa $mahasiswa, StatusKehadiran $status): void
    {
        if ($this->statusDari($mahasiswa) !== null) {
            throw new LogicException($mahasiswa->getNama() . " sudah dicatat pada pertemuan {$this->nomor}");
        }
        $this->presensi[] = new Presensi($mahasiswa, $status);
    }

    public function statusDari(Mahasiswa $mahasiswa): ?StatusKehadiran
    {
        foreach ($this->presensi as $p) {
            if ($p->mahasiswa === $mahasiswa) {
                return $p->status;
            }
        }
        return null;
    }

    /** @return Presensi[] salinan larik; Presensi sendiri readonly */
    public function getPresensi(): array { return $this->presensi; }
    public function getNomor(): int { return $this->nomor; }
    public function getTanggal(): DateTimeImmutable { return $this->tanggal; }
    public function getTopik(): string { return $this->topik; }

    public function __toString(): string
    {
        return sprintf('Pertemuan %d (%s): %s [%d tercatat]',
            $this->nomor, $this->tanggal->format('Y-m-d'), $this->topik, count($this->presensi));
    }
}
