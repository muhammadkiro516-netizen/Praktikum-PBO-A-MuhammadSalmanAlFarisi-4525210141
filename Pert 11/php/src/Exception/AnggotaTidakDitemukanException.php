<?php
declare(strict_types=1);

namespace Perpustakaan\Exception;

/** Anggota belum terdaftar. Membawa idAnggota. */
final class AnggotaTidakDitemukanException extends PerpustakaanException
{
    public function __construct(private readonly string $idAnggota)
    {
        parent::__construct('Anggota dengan ID ' . $idAnggota . ' tidak ditemukan');
    }

    public function getIdAnggota(): string { return $this->idAnggota; }
}
