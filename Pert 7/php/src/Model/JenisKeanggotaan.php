<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/**
 * Kontrak jenis keanggotaan (bukan enum + match), karena akan ada jenis baru.
 * Jenis baru cukup menambah implementasi; Peminjaman dan Anggota tidak disentuh.
 */
interface JenisKeanggotaan
{
    public function nama(): string;

    /** Denda per hari keterlambatan, dalam rupiah. */
    public function dendaPerHari(): int;
}
