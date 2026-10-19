<?php
declare(strict_types=1);

/**
 * Percobaan 4: PHP tidak punya nested class, jadi tidak ada cara bahasa untuk
 * melarang `new Bab(...)` dari luar Buku. Jalankan dari folder percobaan/04-php-bab-dari-luar:
 *     php bab-liar.php
 */
require __DIR__ . '/../../php/src/Model/Bab.php';

use Perpustakaan\Model\Bab;

$liar = new Bab(9, 'Bab liar');   // PHP tidak menolak baris ini
echo "Bab dibuat dari luar Buku tanpa galat: $liar", PHP_EOL;
