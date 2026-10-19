<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/** Status sebuah koleksi. Rusak dan hilang menutup peminjaman apa pun jenisnya. */
enum StatusKoleksi: string
{
    case Tersedia = 'tersedia';
    case Dipinjam = 'dipinjam';
    case Rusak = 'rusak';
    case Hilang = 'hilang';

    public function membolehkanPeminjaman(): bool
    {
        return match ($this) {
            self::Tersedia, self::Dipinjam => true,
            self::Rusak, self::Hilang => false,
        };
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
