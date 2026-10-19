<?php
declare(strict_types=1);

namespace Presensi\Model;

enum StatusKehadiran: string
{
    case Hadir = 'hadir';
    case Izin = 'izin';
    case Sakit = 'sakit';
    case Alpa = 'alpa';

    /** Hanya Hadir yang menambah persentase kehadiran. */
    public function dihitungHadir(): bool
    {
        return $this === self::Hadir;
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
