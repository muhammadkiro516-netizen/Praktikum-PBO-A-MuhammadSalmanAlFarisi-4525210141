<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

final class Dosen implements JenisKeanggotaan
{
    public function nama(): string { return 'Dosen'; }
    public function dendaPerHari(): int { return 500; }
}
