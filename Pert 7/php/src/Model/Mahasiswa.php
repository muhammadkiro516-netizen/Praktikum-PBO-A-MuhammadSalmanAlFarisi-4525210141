<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

final class Mahasiswa implements JenisKeanggotaan
{
    public function nama(): string { return 'Mahasiswa'; }
    public function dendaPerHari(): int { return 1000; }
}
