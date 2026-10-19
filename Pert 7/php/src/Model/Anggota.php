<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use InvalidArgumentException;

/**
 * Anggota perpustakaan. Nomor anggota tidak pernah berubah setelah didaftarkan:
 * properti readonly dan tidak ada setter.
 */
class Anggota
{
    public function __construct(
        private readonly string $nomorAnggota,
        private readonly string $nama,
        private readonly JenisKeanggotaan $jenis,
    ) {
        if (trim($nomorAnggota) === '') {
            throw new InvalidArgumentException('Nomor anggota tidak boleh kosong');
        }
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama tidak boleh kosong');
        }
    }

    public function getNomorAnggota(): string { return $this->nomorAnggota; }
    public function getNama(): string { return $this->nama; }
    public function getJenis(): JenisKeanggotaan { return $this->jenis; }

    public function __toString(): string
    {
        return sprintf('%s %s (%s)', $this->nomorAnggota, $this->nama, $this->jenis->nama());
    }
}
