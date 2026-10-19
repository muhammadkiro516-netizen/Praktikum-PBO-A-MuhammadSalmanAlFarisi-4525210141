<?php
declare(strict_types=1);

namespace Presensi\Model;

use InvalidArgumentException;

/** Mahasiswa hidup sendiri; kelas hanya merujuknya (agregasi), presensi mengaitkannya (asosiasi). */
class Mahasiswa
{
    public function __construct(
        private readonly string $npm,
        private readonly string $nama,
    ) {
        if (trim($npm) === '' || trim($nama) === '') {
            throw new InvalidArgumentException('NPM dan nama mahasiswa wajib diisi');
        }
    }

    public function getNpm(): string { return $this->npm; }
    public function getNama(): string { return $this->nama; }

    public function __toString(): string { return "{$this->npm} {$this->nama}"; }
}
