<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/** Skripsi sama sekali tidak dapat dipinjam; hanya boleh dibaca di tempat. */
class Skripsi extends Koleksi
{
    public function masaPinjamHari(): int { return 0; }
    public function bolehDipinjam(): bool { return false; }
}
