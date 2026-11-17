<?php
declare(strict_types=1);

namespace Perpustakaan\Service;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use LogicException;
use Perpustakaan\Model\Anggota;
use Perpustakaan\Model\Koleksi;
use Perpustakaan\Model\Peminjaman;

/**
 * Layanan perpustakaan. Mengagregasi Koleksi dan Anggota: keduanya dibuat di
 * luar lalu diterima lewat tambahKoleksi() dan daftarkanAnggota().
 */
final class PerpustakaanService
{
    /** @var array<string, Koleksi> */
    private array $koleksi = [];

    /** @var array<string, Anggota> */
    private array $anggota = [];

    public function tambahKoleksi(Koleksi $k): void
    {
        if (isset($this->koleksi[$k->getNomorInduk()])) {
            throw new InvalidArgumentException('Nomor induk ' . $k->getNomorInduk() . ' sudah terdaftar.');
        }
        $this->koleksi[$k->getNomorInduk()] = $k;
    }

    public function daftarkanAnggota(Anggota $a): void
    {
        if (isset($this->anggota[$a->getNomorAnggota()])) {
            throw new InvalidArgumentException('Nomor anggota ' . $a->getNomorAnggota() . ' sudah terdaftar.');
        }
        $this->anggota[$a->getNomorAnggota()] = $a;
    }

    public function cariKoleksi(string $nomorInduk): ?Koleksi
    {
        return $this->koleksi[$nomorInduk] ?? null;
    }

    /** @return Koleksi[] */
    public function daftarKoleksi(): array
    {
        return array_values($this->koleksi);
    }

    public function pinjam(string $nomorAnggota, string $nomorInduk, ?CarbonImmutable $tanggal = null): Peminjaman
    {
        $tanggal ??= CarbonImmutable::now()->startOfDay();
        $a = $this->ambilAnggota($nomorAnggota);
        $k = $this->ambilKoleksi($nomorInduk);

        // Semua pemeriksaan dilakukan sebelum ada data yang berubah.
        if (!$k->bolehDipinjam()) {
            throw new LogicException($k->getJudul() . ' tidak dapat dipinjam, hanya boleh dibaca di tempat.');
        }
        if (!$k->tersedia()) {
            throw new LogicException($k->getJudul() . ' tidak tersedia (status '
                . $k->getStatus()->label() . ', ' . $k->getJumlahEksemplar() . ' eksemplar).');
        }
        if (!$a->bolehMeminjamLagi()) {
            throw new LogicException($a->getNama() . ' sudah meminjam ' . Anggota::BATAS_PINJAMAN
                . ' koleksi (batas maksimum).');
        }

        $p = $a->catatPeminjaman($k, $tanggal);
        $k->kurangiStok();
        return $p;
    }

    public function kembalikan(string $nomorAnggota, string $nomorInduk, ?CarbonImmutable $tanggal = null): Peminjaman
    {
        $tanggal ??= CarbonImmutable::now()->startOfDay();
        $a = $this->ambilAnggota($nomorAnggota);
        $k = $this->ambilKoleksi($nomorInduk);
        foreach ($a->getRiwayat() as $p) {
            if (!$p->sudahKembali() && $p->getKoleksi() === $k) {
                $p->kembalikan($tanggal);
                $k->tambahStok();
                return $p;
            }
        }
        throw new LogicException($a->getNama() . ' tidak sedang meminjam ' . $k->getJudul() . '.');
    }

    /** @return Peminjaman[] semua peminjaman yang belum dikembalikan */
    public function daftarSedangDipinjam(): array
    {
        $hasil = [];
        foreach ($this->anggota as $a) {
            foreach ($a->getRiwayat() as $p) {
                if (!$p->sudahKembali()) {
                    $hasil[] = $p;
                }
            }
        }
        return $hasil;
    }

    /** @return Peminjaman[] */
    public function riwayatAnggota(string $nomorAnggota): array
    {
        return $this->ambilAnggota($nomorAnggota)->getRiwayat();
    }

    private function ambilAnggota(string $nomorAnggota): Anggota
    {
        return $this->anggota[$nomorAnggota]
            ?? throw new InvalidArgumentException('Anggota ' . $nomorAnggota . ' tidak ditemukan.');
    }

    private function ambilKoleksi(string $nomorInduk): Koleksi
    {
        return $this->koleksi[$nomorInduk]
            ?? throw new InvalidArgumentException('Koleksi ' . $nomorInduk . ' tidak ditemukan.');
    }
}
