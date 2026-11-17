<?php
declare(strict_types=1);

namespace Perpustakaan\Model;

/** Agregasi: Mahasiswa datang dari luar dan tetap hidup bila jurusan dibubarkan. */
final class Jurusan
{
    /** @var Mahasiswa[] */
    private array $mahasiswa = [];

    public function __construct(
        private readonly string $kode,
        private readonly string $nama,
    ) {}

    /** Terima objek dari luar. Duplikat (NIM sama) tidak ditambahkan. */
    public function daftarkan(Mahasiswa $m): void
    {
        foreach ($this->mahasiswa as $sudah) {
            if ($sudah->getNim() === $m->getNim()) {
                return;
            }
        }
        $this->mahasiswa[] = $m;
    }

    /** Lepaskan dari jurusan. Objeknya tetap hidup. */
    public function keluarkan(Mahasiswa $m): void
    {
        $this->mahasiswa = array_values(array_filter(
            $this->mahasiswa,
            fn(Mahasiswa $x): bool => $x->getNim() !== $m->getNim()
        ));
    }

    /** @return Mahasiswa[] */
    public function getMahasiswa(): array { return $this->mahasiswa; }

    public function __toString(): string
    {
        return sprintf('%s - %s (%d mahasiswa)', $this->kode, $this->nama, count($this->mahasiswa));
    }
}
