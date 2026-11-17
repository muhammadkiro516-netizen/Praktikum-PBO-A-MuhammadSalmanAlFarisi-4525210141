<?php
declare(strict_types=1);

namespace Perpustakaan\Exception;

/** Anggota sudah mencapai batas peminjaman. Membawa idAnggota dan batas. */
final class BatasPinjamTerlampauiException extends PerpustakaanException
{
    public function __construct(
        private readonly string $idAnggota,
        private readonly int $batas,
    ) {
        parent::__construct('Anggota dengan ID ' . $idAnggota . ' telah mencapai batas maksimum peminjaman');
    }

    public function getIdAnggota(): string { return $this->idAnggota; }

    public function getBatas(): int { return $this->batas; }
}
