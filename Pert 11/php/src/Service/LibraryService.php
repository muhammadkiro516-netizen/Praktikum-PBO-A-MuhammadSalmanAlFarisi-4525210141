<?php
declare(strict_types=1);

namespace Perpustakaan\Service;

use InvalidArgumentException;
use Perpustakaan\Exception\AnggotaTidakDitemukanException;
use Perpustakaan\Exception\BatasPinjamTerlampauiException;
use Perpustakaan\Exception\BukuSedangDipinjamException;
use Perpustakaan\Exception\BukuTidakDitemukanException;
use Perpustakaan\Model\Buku;
use Perpustakaan\Model\KoleksiBuku;

/**
 * LibraryService (guard clause, sesi 10) dengan struktur data koleksi (sesi 11):
 *   - KoleksiBuku  : buku dicari lewat kunci ISBN, tanpa perulangan linear.
 *   - array asosiatif $pinjaman : jumlah pinjaman per anggota.
 */
final class LibraryService
{
    public const int BATAS_PINJAM = 3;

    private KoleksiBuku $buku;

    /** @var array<string,int> idAnggota => jumlah pinjaman */
    private array $pinjaman = [];

    public function __construct()
    {
        $this->buku = new KoleksiBuku();
    }

    public function tambahBuku(Buku $b): void
    {
        $this->buku->simpan($b);
    }

    public function daftarkanAnggota(string $idAnggota): void
    {
        if (trim($idAnggota) === '') {
            throw new InvalidArgumentException('ID anggota tidak boleh kosong.');
        }
        $this->pinjaman[$idAnggota] ??= 0;
    }

    /** Buku atau null: pemanggil memakai ?-> dan ??. */
    public function cariBuku(string $isbn): ?Buku
    {
        return $this->buku->cariByIsbn($isbn);
    }

    /** @return Buku[] */
    public function semuaBuku(): array
    {
        return $this->buku->semua();
    }

    public function jumlahPinjaman(string $idAnggota): int
    {
        return $this->pinjaman[$idAnggota] ?? 0;
    }

    /**
     * Meminjam satu eksemplar. Guard clause: setiap pemeriksaan diakhiri throw.
     *
     * @throws AnggotaTidakDitemukanException bila anggota belum terdaftar
     * @throws BukuTidakDitemukanException    bila ISBN tidak terdaftar
     * @throws BukuSedangDipinjamException    bila semua eksemplar sedang dipinjam
     * @throws BatasPinjamTerlampauiException bila anggota sudah mencapai batas
     */
    public function pinjam(string $idAnggota, string $isbn): void
    {
        if (!array_key_exists($idAnggota, $this->pinjaman)) {
            throw new AnggotaTidakDitemukanException($idAnggota);
        }
        $buku = $this->buku->cariByIsbn($isbn) ?? throw new BukuTidakDitemukanException($isbn);
        if ($buku->getJumlahEksemplar() <= 0) {
            throw new BukuSedangDipinjamException($isbn, $buku->getJudul());
        }
        if ($this->pinjaman[$idAnggota] >= self::BATAS_PINJAM) {
            throw new BatasPinjamTerlampauiException($idAnggota, self::BATAS_PINJAM);
        }

        $buku->kurangiStok();
        $this->pinjaman[$idAnggota]++;
    }
}
