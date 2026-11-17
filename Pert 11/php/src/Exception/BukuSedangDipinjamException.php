<?php
declare(strict_types=1);

namespace Perpustakaan\Exception;

/** Semua eksemplar sedang dipinjam. Membawa isbn dan judul. */
final class BukuSedangDipinjamException extends PerpustakaanException
{
    public function __construct(
        private readonly string $isbn,
        private readonly string $judul,
    ) {
        parent::__construct('Buku ' . $judul . ' dengan ISBN ' . $isbn . ' sedang dipinjam');
    }

    public function getIsbn(): string { return $this->isbn; }

    public function getJudul(): string { return $this->judul; }
}
