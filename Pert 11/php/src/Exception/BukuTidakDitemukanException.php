<?php
declare(strict_types=1);

namespace Perpustakaan\Exception;

/** ISBN tidak terdaftar. Membawa data: isbn dapat diambil lewat getIsbn(). */
final class BukuTidakDitemukanException extends PerpustakaanException
{
    public function __construct(private readonly string $isbn)
    {
        parent::__construct('Buku dengan ISBN ' . $isbn . ' tidak ditemukan');
    }

    public function getIsbn(): string { return $this->isbn; }
}
