<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use InvalidArgumentException;

/**
 * Bagian dari Buku (komposisi). Semua propertinya readonly, sehingga objek Bab
 * yang bocor lewat getBab() tetap tidak bisa diubah.
 *
 * @internal Hanya Buku yang boleh membuat Bab. PHP tidak punya nested class
 *           seperti Java, jadi larangan ini dijaga lewat konvensi (lihat bukti-relasi.md).
 */
final class Bab
{
    public function __construct(
        public readonly int $nomor,
        public readonly string $judul,
    ) {
        if ($nomor <= 0) {
            throw new InvalidArgumentException("Nomor bab harus positif: $nomor");
        }
        if (trim($judul) === '') {
            throw new InvalidArgumentException('Judul bab tidak boleh kosong');
        }
    }

    public function __toString(): string
    {
        return "Bab {$this->nomor}: {$this->judul}";
    }
}
