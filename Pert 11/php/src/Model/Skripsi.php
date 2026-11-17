<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/** Skripsi tidak dapat dipinjam sama sekali; hanya boleh dibaca di tempat. */
final class Skripsi extends Koleksi
{
    public function masaPinjamHari(): int { return 0; }
    public function bolehDipinjam(): bool { return false; }
}
