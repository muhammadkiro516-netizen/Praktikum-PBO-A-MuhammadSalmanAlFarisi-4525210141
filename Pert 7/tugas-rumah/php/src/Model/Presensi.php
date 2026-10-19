<?php
declare(strict_types=1);

namespace Presensi\Model;

/**
 * Bagian dari Pertemuan (komposisi). Properti readonly, jadi objek yang bocor
 * lewat getPresensi() tidak bisa diubah.
 *
 * @internal Hanya Pertemuan yang boleh membuatnya (PHP tidak punya nested class;
 *           disiplin ini dijaga lewat konvensi).
 */
final class Presensi
{
    public function __construct(
        public readonly Mahasiswa $mahasiswa,
        public readonly StatusKehadiran $status,
    ) {}

    public function __toString(): string
    {
        return $this->mahasiswa->getNama() . ': ' . $this->status->label();
    }
}
