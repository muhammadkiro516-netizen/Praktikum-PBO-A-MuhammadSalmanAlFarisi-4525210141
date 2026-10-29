<?php
declare(strict_types=1);

/**
 * Sesi 9 — komposisi dan agregasi (PHP).
 * Bandingkan dengan versi Java.
 */

final class Bab
{
    /**
     * Agar benar-benar komposisi, kelas ini hanya boleh dibuat dari dalam
     * Buku::tambahBab(). PHP tidak punya nested class seperti Java
     * (constructor privat pada kelas bersarang), jadi disiplin ini dijaga
     * lewat konvensi: @internal, dan tidak ada method Buku yang menerima
     * objek Bab dari luar. Catatannya ada di bukti-relasi.md.
     *
     * @internal Jangan dibuat di luar Buku.
     */
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

final class Buku
{
    /** @var Bab[] */
    private array $bab = [];

    public function __construct(
        private readonly string $nomorInduk,
        private readonly string $judul,
        private readonly string $penulis,
        private int $jumlahEksemplar,
    ) {
        // Jumlah eksemplar tidak boleh negatif.
        if ($jumlahEksemplar < 0) {
            throw new InvalidArgumentException('Jumlah eksemplar tidak boleh negatif: ' . $jumlahEksemplar);
        }
    }

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
     * PHP mengembalikan array secara by-value, sehingga pemanggil hanya
     * memperoleh salinan larik-nya. Namun objek DI DALAMNYA tetap berbagi
     * referensi — itulah sebabnya Bab dibuat readonly.
     * Tuliskan penjelasan ini di bukti-relasi.md.
     *
     * @return Bab[]
     */
    public function getBab(): array { return $this->bab; }

    public function tersedia(): bool { return $this->jumlahEksemplar > 0; }

    /** Kurangi stok; tolak bila sudah nol. */
    public function kurangiStok(): void
    {
        if ($this->jumlahEksemplar === 0) {
            throw new LogicException('Tidak ada eksemplar tersedia untuk ' . $this->nomorInduk . '.');
        }
        $this->jumlahEksemplar--;
    }

    public function tambahStok(): void { $this->jumlahEksemplar++; }

    public function getNomorInduk(): string { return $this->nomorInduk; }
    public function getJudul(): string { return $this->judul; }

    public function __toString(): string
    {
        return sprintf('%-32s %-16s [%d eks, %d bab]',
            $this->judul, $this->penulis, $this->jumlahEksemplar, count($this->bab));
    }
}

final class Mahasiswa
{
    public function __construct(
        private readonly string $nim,
        private readonly string $nama,
    ) {}

    public function getNim(): string { return $this->nim; }
    public function __toString(): string { return $this->nim . ' ' . $this->nama; }
}

final class Jurusan
{
    /** @var Mahasiswa[] */
    private array $mahasiswa = [];

    public function __construct(
        private readonly string $kode,
        private readonly string $nama,
    ) {}

    /** Terima objek dari luar (agregasi). Duplikat (NIM sama) tidak ditambahkan. */
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
