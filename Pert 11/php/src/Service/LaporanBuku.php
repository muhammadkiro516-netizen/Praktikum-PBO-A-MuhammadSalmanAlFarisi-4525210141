<?php
declare(strict_types=1);

namespace Perpustakaan\Service;

use Perpustakaan\Model\Buku;
use Perpustakaan\Model\KoleksiBuku;

/**
 * Sesi 11 Langkah 4 — tiga laporan memakai operasi fungsional,
 * tanpa perulangan manual di sini.
 */
final class LaporanBuku
{
    /** 1. Filter: buku yang tersedia. */
    public static function tersedia(KoleksiBuku $koleksi): KoleksiBuku
    {
        return $koleksi->filter(fn(Buku $b): bool => $b->tersedia());
    }

    /** 2. Map + join: judul saja, digabung dengan pemisah. */
    public static function judulGabung(KoleksiBuku $koleksi, string $pemisah): string
    {
        return implode($pemisah, $koleksi->map(fn(Buku $b): string => $b->getJudul()));
    }

    /**
     * 3. Pengelompokan: jumlah buku per penulis.
     *
     * @return array<string,int>
     */
    public static function jumlahPerPenulis(KoleksiBuku $koleksi): array
    {
        return array_map('count', $koleksi->kelompokkan(fn(Buku $b): string => $b->getPenulis()));
    }
}
