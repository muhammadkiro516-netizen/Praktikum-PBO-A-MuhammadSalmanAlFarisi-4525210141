<?php
declare(strict_types=1);

namespace Perpustakaan\Tests;

use Perpustakaan\Model\Buku;
use Perpustakaan\Model\KoleksiBuku;
use PHPUnit\Framework\TestCase;

/**
 * Sesi 11 — unit test PHPUnit untuk typed collection KoleksiBuku.
 * Jalankan: ./vendor/bin/phpunit tests
 */
final class KoleksiBukuTest extends TestCase
{
    private KoleksiBuku $koleksi;

    protected function setUp(): void
    {
        // Dijalankan ulang sebelum SETIAP pengujian.
        $this->koleksi = new KoleksiBuku();
    }

    private function buku(string $isbn, string $judul, string $penulis = 'Anon', int $eks = 1): Buku
    {
        return new Buku($isbn, $judul, $penulis, 2020, $eks);
    }

    public function testItemYangDisimpanDapatDitemukanKembali(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'PBO'));

        $this->assertNotNull($this->koleksi->cariByIsbn('978-1'));
        $this->assertSame('PBO', $this->koleksi->cariByIsbn('978-1')?->getJudul());
    }

    public function testMencariIsbnYangTidakAdaMengembalikanNull(): void
    {
        $this->assertNull($this->koleksi->cariByIsbn('999'));
        $this->assertSame('(tidak ada)', $this->koleksi->cariByIsbn('999')?->getJudul() ?? '(tidak ada)');
    }

    public function testKoleksiBaruBerisiNolItem(): void
    {
        // count() bekerja karena KoleksiBuku implements Countable.
        $this->assertCount(0, $this->koleksi);
        $this->assertSame([], $this->koleksi->semua());
    }

    public function testFilterHanyaMengembalikanYangLolosPredikat(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'A', 'X', 2));
        $this->koleksi->simpan($this->buku('978-2', 'B', 'X', 0));
        $this->koleksi->simpan($this->buku('978-3', 'C', 'Y', 1));

        $tersedia = $this->koleksi->filter(fn(Buku $b): bool => $b->tersedia());

        $this->assertCount(2, $tersedia);
        $this->assertSame(['A', 'C'], $tersedia->map(fn(Buku $b): string => $b->getJudul()));
    }

    public function testJumlahkanMenghitungTotalDenganBenar(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'A', 'X', 2));
        $this->koleksi->simpan($this->buku('978-2', 'B', 'X', 3));

        $total = $this->koleksi->jumlahkan(fn(Buku $b): int => $b->getJumlahEksemplar());

        $this->assertSame(5.0, $total);
    }

    public function testSimpanDenganKunciSamaMenimpa(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'Judul Lama'));
        $this->koleksi->simpan($this->buku('978-1', 'Judul Baru'));

        $this->assertCount(1, $this->koleksi);
        $this->assertSame('Judul Baru', $this->koleksi->cariByIsbn('978-1')?->getJudul());
    }

    public function testMapMengembalikanLarikBerurutan(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'A'));
        $this->koleksi->simpan($this->buku('978-2', 'B'));

        $this->assertSame(['A', 'B'], $this->koleksi->map(fn(Buku $b): string => $b->getJudul()));
    }

    public function testKelompokkanPerPenulis(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'A', 'X'));
        $this->koleksi->simpan($this->buku('978-2', 'B', 'Y'));
        $this->koleksi->simpan($this->buku('978-3', 'C', 'X'));

        $kelompok = $this->koleksi->kelompokkan(fn(Buku $b): string => $b->getPenulis());

        $this->assertSame(['X', 'Y'], array_keys($kelompok));
        $this->assertCount(2, $kelompok['X']);
        $this->assertCount(1, $kelompok['Y']);
    }

    public function testFilterTidakMengubahKoleksiAsal(): void
    {
        $this->koleksi->simpan($this->buku('978-1', 'A', 'X', 0));

        $this->koleksi->filter(fn(Buku $b): bool => $b->tersedia());

        $this->assertCount(1, $this->koleksi);
    }
}
