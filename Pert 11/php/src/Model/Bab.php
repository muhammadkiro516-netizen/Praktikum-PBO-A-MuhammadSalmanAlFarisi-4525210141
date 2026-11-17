<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/**
 * Bagian dari Buku (komposisi). PHP tidak punya kelas bersarang, jadi aturan
 * "hanya dibuat dari dalam Buku::tambahBab()" dijaga lewat konvensi.
 *
 * @internal Jangan dibuat di luar Buku.
 */
final class Bab
{
    public function __construct(
        private readonly int $nomor,
        private readonly string $judul,
    ) {}

    public function getNomor(): int { return $this->nomor; }
    public function getJudul(): string { return $this->judul; }

    public function __toString(): string
    {
        return 'Bab ' . $this->nomor . ': ' . $this->judul;
    }
}
