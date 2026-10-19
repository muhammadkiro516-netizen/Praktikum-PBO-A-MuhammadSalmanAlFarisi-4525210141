<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/** Majalah hanya dapat dipinjam selama 3 hari. */
class Majalah extends Koleksi
{
    private const MASA_PINJAM_HARI = 3;

    public function masaPinjamHari(): int { return self::MASA_PINJAM_HARI; }
    public function bolehDipinjam(): bool { return true; }
}
