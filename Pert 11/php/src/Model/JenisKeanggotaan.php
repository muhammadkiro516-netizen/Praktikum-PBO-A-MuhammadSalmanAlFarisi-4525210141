<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/**
 * Kontrak jenis keanggotaan. Jenis baru cukup berupa satu kelas baru yang
 * mengimplementasikan interface ini, tanpa menyunting kelas lain (Open-Closed).
 */
interface JenisKeanggotaan
{
    public function nama(): string;

    public function dendaPerHari(): int;
}
