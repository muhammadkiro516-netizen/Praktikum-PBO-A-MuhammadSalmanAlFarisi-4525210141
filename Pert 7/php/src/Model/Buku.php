<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use InvalidArgumentException;

/**
 * Sesi 7 - KOMPOSISI: Buku memiliki 1..* Bab.
 *  1. tidak ada constructor/method yang menerima objek Bab dari luar
 *  2. Bab dibuat DI DALAM Buku
 *  3. getBab() mengembalikan salinan larik, dan Bab sendiri readonly
 * Multiplicity 1..* dijaga constructor: daftar judul bab tidak boleh kosong.
 */
class Buku extends Koleksi
{
    private const MASA_PINJAM_HARI = 14;

    /** @var Bab[] */
    private array $bab = [];

    /** @param string[] $judulBab judul bab awal (bukan objek Bab), minimal satu */
    public function __construct(
        string $nomorInduk,
        string $judul,
        string $penulis,
        int $tahun,
        int $jumlahEksemplar,
        array $judulBab,
    ) {
        parent::__construct($nomorInduk, $judul, $penulis, $tahun, $jumlahEksemplar);
        // Menjamin multiplicity 1..* pada diagram: buku tanpa bab tidak pernah lahir.
        if (count($judulBab) === 0) {
            throw new InvalidArgumentException('Buku harus memiliki minimal satu bab');
        }
        foreach ($judulBab as $j) {
            $this->buatBab(count($this->bab) + 1, $j);
        }
    }

    /** Satu-satunya cara Bab lahir. Parameternya nomor dan judul, bukan objek Bab. */
    public function tambahBab(int $nomor, string $judulBab): void
    {
        $this->buatBab($nomor, $judulBab);
    }

    private function buatBab(int $nomor, string $judulBab): void
    {
        foreach ($this->bab as $b) {
            if ($b->nomor === $nomor) {
                throw new InvalidArgumentException("Bab nomor $nomor sudah ada");
            }
        }
        $this->bab[] = new Bab($nomor, $judulBab);
    }

    /**
     * PHP mengembalikan array by-value: pemanggil hanya memperoleh SALINAN larik,
     * mengosongkannya tidak memengaruhi koleksi internal. Objek di dalamnya tetap
     * berbagi referensi, itulah sebabnya Bab dibuat readonly.
     *
     * @return Bab[]
     */
    public function getBab(): array
    {
        return $this->bab;
    }

    public function masaPinjamHari(): int { return self::MASA_PINJAM_HARI; }
    public function bolehDipinjam(): bool { return true; }

    public function __toString(): string
    {
        return parent::__toString() . ' {' . count($this->bab) . ' bab}';
    }
}
