<?php
declare(strict_types=1);

/**
 * Sesi 1 — objek pertama (PHP).
 *
 * Bandingkan baris demi baris dengan java/HaloObjek.java.
 * Konsepnya sama persis; hanya sintaksnya yang berbeda.
 *
 * Jalankan:
 *     php halo_objek.php
 */
class HaloObjek
{
    // Properti dibuat private: data objek tidak seharusnya terbuka bagi siapa pun.
    private string $nama = '';
    private string $nim = '';

    public function __construct(string $nama, string $nim)
    {
        // Padanan `this` pada Java adalah `$this` di PHP,
        // dan aksesnya memakai `->` bukan titik.
        $this->nama = $nama;
        $this->nim = $nim;
    }

    /**
     * Mengembalikan satu baris sapaan yang disusun dari PROPERTI objek.
     */
    public function sapa(): string
    {
        return 'Halo, saya ' . $this->nama . ' (' . $this->nim . ')';
    }
}

$saya = new HaloObjek('Muhammad Salman Al Farisi', '4525210141');
echo $saya->sapa(), PHP_EOL;

$temanSekelas = new HaloObjek('Budi Santoso', '2024002');
echo $temanSekelas->sapa(), PHP_EOL;

echo PHP_EOL;
echo 'Pertanyaan untuk direnungkan:', PHP_EOL;
echo '  Apa padanan kata kunci "this" Java di baris-baris di atas?', PHP_EOL;
echo '  Mengapa PHP memakai -> sedangkan Java memakai titik?', PHP_EOL;
echo '  Mengapa kedua objek di atas bisa menyapa dengan nama berbeda,', PHP_EOL;
echo '  padahal method sapa() hanya ditulis satu kali?', PHP_EOL;
