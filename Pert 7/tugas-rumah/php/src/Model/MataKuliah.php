<?php
declare(strict_types=1);

namespace Presensi\Model;

use InvalidArgumentException;

class MataKuliah
{
    public function __construct(
        private readonly string $kode,
        private readonly string $nama,
        private readonly int $sks,
    ) {
        if (trim($kode) === '' || trim($nama) === '') {
            throw new InvalidArgumentException('Kode dan nama mata kuliah wajib diisi');
        }
        if ($sks <= 0) {
            throw new InvalidArgumentException("SKS harus positif: $sks");
        }
    }

    public function getKode(): string { return $this->kode; }
    public function getNama(): string { return $this->nama; }
    public function getSks(): int { return $this->sks; }

    public function __toString(): string { return "{$this->kode} {$this->nama} ({$this->sks} SKS)"; }
}
