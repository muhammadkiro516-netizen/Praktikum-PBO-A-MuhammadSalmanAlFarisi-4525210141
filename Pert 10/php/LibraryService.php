<?php
declare(strict_types=1);

require_once __DIR__ . '/exceptions.php';

final class LibraryService
{
    public const BATAS_PINJAM = 3;

    /** @var array<string,int> */
    private array $stok = [];
    /** @var array<string,string> */
    private array $judul = [];
    /** @var array<string,int> */
    private array $pinjaman = [];

    public function tambahBuku(string $isbn, string $judulBuku, int $eksemplar): void
    {
        $this->stok[$isbn]  = ($this->stok[$isbn]  ?? 0) + $eksemplar;
        $this->judul[$isbn] = $judulBuku;
    }

    public function daftarkanAnggota(string $idAnggota): void
    {
        $this->pinjaman[$idAnggota] ??= 0;
    }

    /**
     * Guard clause: periksa yang salah lebih dulu — lempar exception —
     * sehingga logika utama tetap rata di kiri.
     */
    public function pinjam(string $idAnggota, string $isbn): void
    {
        if (!array_key_exists($idAnggota, $this->pinjaman))
            throw new AnggotaTidakDitemukanException($idAnggota);

        if (!array_key_exists($isbn, $this->stok))
            throw new BukuTidakDitemukanException($isbn);

        if ($this->stok[$isbn] <= 0)
            throw new BukuSedangDipinjamException($isbn, $this->judul[$isbn]);

        if ($this->pinjaman[$idAnggota] >= self::BATAS_PINJAM)
            throw new BatasPinjamTerlampauiException($idAnggota, self::BATAS_PINJAM);

        $this->stok[$isbn]--;
        $this->pinjaman[$idAnggota]++;
    }

    public function sisaEksemplar(string $isbn): int
    {
        return $this->stok[$isbn] ?? 0;
    }

    public function judulDari(string $isbn): ?string
    {
        return $this->judul[$isbn] ?? null;
    }

    public function semuaIsbn(): array
    {
        return array_keys($this->stok);
    }

    /**
     * Ekspor daftar buku ke file.
     * PHP tidak punya try-with-resources, jadi penutupan dilakukan di finally.
     */
    public function eksporDaftarBuku(string $namaFile): void
    {
        $fp = fopen($namaFile, 'w');
        if ($fp === false) {
            throw new \RuntimeException("Tidak dapat membuka file: {$namaFile}");
        }
        try {
            fwrite($fp, "=== Daftar Buku Perpustakaan ===\n");
            foreach ($this->stok as $isbn => $jumlah) {
                fwrite($fp, sprintf("ISBN: %-20s | Judul: %-40s | Stok: %d\n",
                    $isbn, $this->judul[$isbn], $jumlah));
            }
        } finally {
            fclose($fp); // ditutup meski exception terjadi di tengah
        }
    }

    public function log(string $namaFile, \Throwable $e): void
    {
        $baris = '[' . date('Y-m-d H:i:s') . '] '
            . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
        file_put_contents($namaFile, $baris, FILE_APPEND);
    }
}
