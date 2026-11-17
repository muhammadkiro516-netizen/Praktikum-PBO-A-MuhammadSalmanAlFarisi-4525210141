<?php
declare(strict_types=1);

namespace Perpustakaan\Tests;

use Perpustakaan\Exception\AnggotaTidakDitemukanException;
use Perpustakaan\Exception\BatasPinjamTerlampauiException;
use Perpustakaan\Exception\BukuSedangDipinjamException;
use Perpustakaan\Exception\BukuTidakDitemukanException;
use Perpustakaan\Model\Buku;
use Perpustakaan\Service\LibraryService;
use PHPUnit\Framework\TestCase;

/**
 * Skenario kegagalan dari skenario-uji.md (sesi 10) yang diubah menjadi
 * kasus uji otomatis. Nomor skenario disebut pada nama method.
 */
final class LibraryServiceTest extends TestCase
{
    private LibraryService $layanan;

    protected function setUp(): void
    {
        $this->layanan = new LibraryService();
        $this->layanan->tambahBuku(new Buku('978-1', 'Basis Data', 'R. Elmasri', 2016, 1));
        $this->layanan->tambahBuku(new Buku('978-2', 'PBO', 'A. W. Pribadi', 2020, 5));
        $this->layanan->daftarkanAnggota('A-001');
        $this->layanan->daftarkanAnggota('A-002');
    }

    public function testSkenario1IsbnTidakTerdaftarMelemparBukuTidakDitemukan(): void
    {
        try {
            $this->layanan->pinjam('A-001', '978-9');
            $this->fail('BukuTidakDitemukanException seharusnya dilempar.');
        } catch (BukuTidakDitemukanException $e) {
            $this->assertSame('978-9', $e->getIsbn());
            $this->assertSame('Buku dengan ISBN 978-9 tidak ditemukan', $e->getMessage());
        }
    }

    public function testSkenario2AnggotaTidakTerdaftarMelemparAnggotaTidakDitemukan(): void
    {
        $this->expectException(AnggotaTidakDitemukanException::class);

        $this->layanan->pinjam('A-009', '978-1');
    }

    public function testSkenario3AnggotaDiperiksaLebihDuluBilaKeduanyaSalah(): void
    {
        $this->expectException(AnggotaTidakDitemukanException::class);

        $this->layanan->pinjam('A-009', '978-9');
    }

    public function testSkenario4StokHabisMelemparBukuSedangDipinjam(): void
    {
        $this->layanan->pinjam('A-001', '978-1');   // satu-satunya eksemplar

        try {
            $this->layanan->pinjam('A-002', '978-1');
            $this->fail('BukuSedangDipinjamException seharusnya dilempar.');
        } catch (BukuSedangDipinjamException $e) {
            $this->assertSame('978-1', $e->getIsbn());
            $this->assertSame('Basis Data', $e->getJudul());
        }
    }

    public function testSkenario5PinjamanKeEmpatMelemparBatasPinjamTerlampaui(): void
    {
        for ($i = 0; $i < LibraryService::BATAS_PINJAM; $i++) {
            $this->layanan->pinjam('A-001', '978-2');
        }

        try {
            $this->layanan->pinjam('A-001', '978-2');
            $this->fail('BatasPinjamTerlampauiException seharusnya dilempar.');
        } catch (BatasPinjamTerlampauiException $e) {
            $this->assertSame('A-001', $e->getIdAnggota());
            $this->assertSame(3, $e->getBatas());
        }
    }

    public function testPinjamBerhasilMengurangiStokDanMenambahHitunganPinjaman(): void
    {
        $this->layanan->pinjam('A-001', '978-2');

        $this->assertSame(4, $this->layanan->cariBuku('978-2')?->getJumlahEksemplar());
        $this->assertSame(1, $this->layanan->jumlahPinjaman('A-001'));
    }

    public function testPinjamYangGagalTidakMengubahStok(): void
    {
        try {
            $this->layanan->pinjam('A-009', '978-2');
        } catch (AnggotaTidakDitemukanException) {
            // diharapkan
        }

        $this->assertSame(5, $this->layanan->cariBuku('978-2')?->getJumlahEksemplar());
    }

    public function testCariBukuYangTidakAdaMengembalikanNull(): void
    {
        $this->assertNull($this->layanan->cariBuku('999'));
    }
}
