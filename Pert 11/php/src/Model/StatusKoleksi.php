<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/** Status koleksi. Himpunannya tetap, sehingga enum lebih tepat daripada string bebas. */
enum StatusKoleksi: string
{
    case Tersedia = 'tersedia';
    case Dipinjam = 'dipinjam';
    case Rusak = 'rusak';
    case Hilang = 'hilang';

    public function label(): string
    {
        return match ($this) {
            self::Tersedia => 'Tersedia',
            self::Dipinjam => 'Dipinjam',
            self::Rusak => 'Rusak',
            self::Hilang => 'Hilang',
        };
    }

    /** Koleksi rusak atau hilang tidak dapat dipinjam, apa pun jenisnya. */
    public function bisaDipinjam(): bool
    {
        return $this === self::Tersedia;
    }
}
