<?php
declare(strict_types=1);

require_once __DIR__ . '/exceptions.php';
require_once __DIR__ . '/LibraryService.php';

$layanan = new LibraryService();
$layanan->tambahBuku('978-0-13-468599-1', 'Effective Java',             2);
$layanan->tambahBuku('978-0-13-110362-7', 'The C Programming Language', 1);
$layanan->tambahBuku('978-0-20-163361-5', 'The Pragmatic Programmer',   1);
$layanan->daftarkanAnggota('A001');
$layanan->daftarkanAnggota('A002');

$logFile = __DIR__ . '/perpustakaan.log';

function demo(LibraryService $layanan, string $id, string $isbn, string $logFile): void
{
    try {
        $layanan->pinjam($id, $isbn);
        printf("  [OK] %s meminjam ISBN %s\n", $id, $isbn);
    } catch (PerpustakaanException $e) {
        $layanan->log($logFile, $e);
        printf("  [!] %-42s -> %s\n", get_class($e), $e->getMessage());
    }
}

echo "=== Sistem Perpustakaan — Sesi 10: Exception Handling (PHP) ===\n\n";

echo "--- Demo otomatis: skenario kegagalan ---\n";
demo($layanan, 'A001', '978-0-13-468599-1', $logFile);  // berhasil
demo($layanan, 'TIDAK_ADA', '978-0-13-468599-1', $logFile); // AnggotaTidakDitemukan
demo($layanan, 'A001', 'ISBN-TIDAK-ADA', $logFile);          // BukuTidakDitemukan
demo($layanan, 'A001', '978-0-13-110362-7', $logFile);  // berhasil (stok 1)
demo($layanan, 'A002', '978-0-13-110362-7', $logFile);  // BukuSedangDipinjam
demo($layanan, 'A001', '978-0-20-163361-5', $logFile);  // berhasil (pinjaman ke-3)
demo($layanan, 'A001', '978-0-13-468599-1', $logFile);  // BatasPinjamTerlampaui (stok ada, tapi batas 3)

echo "\n--- Demo ekspor daftar buku (try-finally) ---\n";
try {
    $eksporFile = __DIR__ . '/daftar-buku.txt';
    $layanan->eksporDaftarBuku($eksporFile);
    echo "  Ekspor berhasil -> daftar-buku.txt\n";
} catch (\RuntimeException $e) {
    echo "  Gagal ekspor: " . $e->getMessage() . "\n";
}

echo "\n--- Tangkap satu blok PerpustakaanException, ambil data spesifik ---\n";
$skenario = [
    ['A001', 'ISBN-X'],
    ['ID-X',  '978-0-13-468599-1'],
];
foreach ($skenario as [$id, $isbn]) {
    try {
        $layanan->pinjam($id, $isbn);
    } catch (PerpustakaanException $e) {
        $layanan->log($logFile, $e);
        if ($e instanceof BukuTidakDitemukanException) {
            echo "  [!] Buku tidak ada: ISBN=" . $e->getIsbn() . "\n";
        } elseif ($e instanceof AnggotaTidakDitemukanException) {
            echo "  [!] Anggota tidak ada: ID=" . $e->getIdAnggota() . "\n";
        } elseif ($e instanceof BukuSedangDipinjamException) {
            echo "  [!] Stok habis: \"" . $e->getJudul() . "\" (ISBN=" . $e->getIsbn() . ")\n";
        } elseif ($e instanceof BatasPinjamTerlampauiException) {
            echo "  [!] Batas pinjam: " . $e->getBatas() . " koleksi (ID=" . $e->getIdAnggota() . ")\n";
        }
    }
}

echo "\nProgram PHP selesai. Log exception: perpustakaan.log\n";
