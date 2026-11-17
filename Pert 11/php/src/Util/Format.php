<?php
declare(strict_types=1);

namespace Perpustakaan\Util;

use Carbon\CarbonInterface;

/** Pemformat bersama. Semua method statis. */
final class Format
{
    /** 5000 menjadi "Rp5.000". */
    public static function rupiah(int $nilai): string
    {
        return 'Rp' . number_format($nilai, 0, ',', '.');
    }

    /** 2026-10-15 menjadi "15 Oktober 2026". */
    public static function tanggal(CarbonInterface $tanggal): string
    {
        return $tanggal->locale('id')->translatedFormat('j F Y');
    }
}
