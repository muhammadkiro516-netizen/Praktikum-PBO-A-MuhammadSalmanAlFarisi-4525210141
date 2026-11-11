<?php
declare(strict_types=1);

/**
 * Sesi 10 — hierarki exception domain (PHP).
 *
 * PHP tidak mengenal checked exception; seluruh exception setara dengan
 * unchecked di Java. Karena itu anotasi @throws pada PHPDoc menjadi jauh
 * lebih penting: pembaca kode tidak dibantu kompilator.
 */

abstract class PerpustakaanException extends RuntimeException
{
}

final class BukuTidakDitemukanException extends PerpustakaanException
{
    /**
     * TODO 1: gunakan constructor property promotion agar $isbn tersimpan
     *         sebagai properti readonly, lalu panggil parent::__construct()
     *         dengan pesan yang menyebut ISBN-nya.
     */
    public function __construct(string $isbn)
    {
        parent::__construct('TODO 1 belum dikerjakan');
    }

    // TODO 2: sediakan getter getIsbn().
}

final class AnggotaTidakDitemukanException extends PerpustakaanException
{
    // TODO 3: lengkapi mengikuti pola di atas. Membawa $idAnggota.
    public function __construct(string $idAnggota)
    {
        parent::__construct('TODO 3 belum dikerjakan');
    }
}

final class BukuSedangDipinjamException extends PerpustakaanException
{
    // TODO 4: membawa $isbn DAN $judul.
    public function __construct(string $isbn, string $judul)
    {
        parent::__construct('TODO 4 belum dikerjakan');
    }
}

final class BatasPinjamTerlampauiException extends PerpustakaanException
{
    // TODO 5: membawa $idAnggota dan $batas.
    public function __construct(string $idAnggota, int $batas)
    {
        parent::__construct('TODO 5 belum dikerjakan');
    }
}
