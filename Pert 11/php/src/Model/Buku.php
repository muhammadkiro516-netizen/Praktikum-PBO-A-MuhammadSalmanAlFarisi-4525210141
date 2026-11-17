<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

use InvalidArgumentException;

/** Buku: dapat dipinjam 14 hari. Memiliki Bab (komposisi). */
final class Buku extends Koleksi
{
    private const MASA_PINJAM_HARI = 14;

    /** @var Bab[] */
    private array $bab = [];

    /** Satu-satunya cara Bab lahir. Parameternya nomor dan judul, bukan objek Bab. */
    public function tambahBab(int $nomor, string $judulBab): void
    {
        if ($nomor <= 0) {
            throw new InvalidArgumentException('Nomor bab harus lebih besar dari 0.');
        }
        if (trim($judulBab) === '') {
            throw new InvalidArgumentException('Judul bab tidak boleh kosong.');
        }
        foreach ($this->bab as $b) {
            if ($b->getNomor() === $nomor) {
                throw new InvalidArgumentException('Bab nomor ' . $nomor . ' sudah ada.');
            }
        }
        $this->bab[] = new Bab($nomor, $judulBab);
    }

    /**
     * Array dikembalikan by-value, sehingga pemanggil hanya memperoleh salinan larik.
     * Objek Bab di dalamnya readonly, jadi tidak bisa diubah dari luar.
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
        return sprintf('%-32s %-16s [%d eks, %d bab]',
            $this->getJudul(), $this->getPenulis(), $this->getJumlahEksemplar(), count($this->bab));
    }
}
