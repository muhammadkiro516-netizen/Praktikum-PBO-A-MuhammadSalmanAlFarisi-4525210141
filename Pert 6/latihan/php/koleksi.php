<?php
declare(strict_types=1);

// Langkah 6 - dibuat mandiri, tanpa starter.

/** Kontrak "bisa dipinjam": APA YANG BISA dilakukan, bukan APA benda ini. */
interface Peminjamable
{
    public function bolehDipinjam(): bool;
    public function masaPinjamHari(): int;
}

/** Enum dengan perilaku: setiap case tahu keterangannya sendiri. */
enum StatusPinjam: string
{
    case Tersedia  = 'tersedia';
    case Dipinjam  = 'dipinjam';
    case Terlambat = 'terlambat';
    case Hilang    = 'hilang';

    public function keterangan(): string
    {
        return match ($this) {
            self::Tersedia  => 'Ada di rak dan bisa dipinjam',
            self::Dipinjam  => 'Sedang dipinjam anggota',
            self::Terlambat => 'Lewat tenggat, denda berjalan',
            self::Hilang    => 'Tidak ditemukan, perlu penggantian',
        };
    }
}

/** Identitas: apa benda ini. Kode dan judul sama di semua koleksi. */
abstract class Koleksi
{
    public function __construct(
        private readonly string $kode,
        private readonly string $judul,
    ) {
        if (trim($kode) === '') {
            throw new InvalidArgumentException('Kode koleksi tidak boleh kosong.');
        }
        if (trim($judul) === '') {
            throw new InvalidArgumentException('Judul koleksi tidak boleh kosong.');
        }
    }

    abstract public function jenis(): string;

    public function getKode(): string  { return $this->kode; }
    public function getJudul(): string { return $this->judul; }

    public function __toString(): string
    {
        return sprintf('%-8s %-8s %s', $this->kode, $this->jenis(), $this->judul);
    }
}

/** Buku: dipinjam 14 hari. */
class Buku extends Koleksi implements Peminjamable
{
    private const MASA_PINJAM_HARI = 14;

    public function jenis(): string          { return 'BUKU'; }
    public function bolehDipinjam(): bool    { return true; }
    public function masaPinjamHari(): int    { return self::MASA_PINJAM_HARI; }
}

/** Majalah: dipinjam 3 hari. */
class Majalah extends Koleksi implements Peminjamable
{
    private const MASA_PINJAM_HARI = 3;

    public function jenis(): string          { return 'MAJALAH'; }
    public function bolehDipinjam(): bool    { return true; }
    public function masaPinjamHari(): int    { return self::MASA_PINJAM_HARI; }
}

/** Skripsi: koleksi langka, hanya dibaca di tempat, tidak boleh dipinjam. */
class Skripsi extends Koleksi implements Peminjamable
{
    public function jenis(): string          { return 'SKRIPSI'; }
    public function bolehDipinjam(): bool    { return false; }
    public function masaPinjamHari(): int    { return 0; }
}
