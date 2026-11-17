<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

final class KeanggotaanKehormatan implements JenisKeanggotaan
{
    public function nama(): string { return 'Anggota Kehormatan'; }
    public function dendaPerHari(): int { return 0; }
}
