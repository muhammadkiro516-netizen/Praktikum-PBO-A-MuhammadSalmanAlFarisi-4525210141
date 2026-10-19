<?php
declare(strict_types=1);

namespace Presensi\Model;

use InvalidArgumentException;

class Dosen
{
    public function __construct(
        private readonly string $nip,
        private readonly string $nama,
    ) {
        if (trim($nip) === '' || trim($nama) === '') {
            throw new InvalidArgumentException('NIP dan nama dosen wajib diisi');
        }
    }

    public function getNip(): string { return $this->nip; }
    public function getNama(): string { return $this->nama; }

    public function __toString(): string { return "{$this->nama} ({$this->nip})"; }
}
