<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

final class KeanggotaanMahasiswa implements JenisKeanggotaan
{
    public function nama(): string { return 'Mahasiswa'; }
    public function dendaPerHari(): int { return 1000; }
}
