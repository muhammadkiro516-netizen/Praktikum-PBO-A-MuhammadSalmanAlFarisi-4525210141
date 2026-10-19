<?php
declare(strict_types=1);

namespace Presensi\Service;

use Presensi\Model\KelasKuliah;
use Presensi\Model\Mahasiswa;
use Presensi\Model\StatusKehadiran;

/**
 * DEPENDENSI: RekapPresensi hanya memakai KelasKuliah sebagai parameter,
 * tidak menyimpannya sebagai bagian dirinya.
 */
class RekapPresensi
{
    public const BATAS_LAYAK_UJIAN = 75.0;

    /** Persentase = pertemuan berstatus Hadir / seluruh pertemuan yang sudah dibuka. */
    public function persentaseKehadiran(KelasKuliah $kelas, Mahasiswa $m): float
    {
        $total = count($kelas->getPertemuan());
        if ($total === 0) {
            return 0.0;
        }
        $hadir = 0;
        foreach ($kelas->getPertemuan() as $p) {
            if ($p->statusDari($m)?->dihitungHadir()) {
                $hadir++;
            }
        }
        return $hadir * 100.0 / $total;
    }

    public function layakUjian(KelasKuliah $kelas, Mahasiswa $m): bool
    {
        return $this->persentaseKehadiran($kelas, $m) >= self::BATAS_LAYAK_UJIAN;
    }

    public function hitung(KelasKuliah $kelas, Mahasiswa $m, StatusKehadiran $status): int
    {
        $n = 0;
        foreach ($kelas->getPertemuan() as $p) {
            if ($p->statusDari($m) === $status) {
                $n++;
            }
        }
        return $n;
    }
}
