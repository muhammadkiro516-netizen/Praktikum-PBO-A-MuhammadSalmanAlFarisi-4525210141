<?php
declare(strict_types=1);

require_once __DIR__ . '/abstraksi.php';

/** Menjalankan satu percobaan dan melaporkan diterima atau ditolak. */
function coba(string $nama, callable $aksi): void
{
    try {
        $aksi();
        printf('  DITERIMA  %-34s%s', $nama, PHP_EOL);
    } catch (InvalidArgumentException $e) {
        printf('  DITOLAK   %-34s -> %s%s', $nama, $e->getMessage(), PHP_EOL);
    }
}

echo '=== Uji validasi ===', PHP_EOL;
coba('Mobil.isiBahanBakar(20) pada 45 L', fn() => (new Mobil('Avanza', 2022, 45))->isiBahanBakar(20));
coba('Mobil.isiBahanBakar(0)',            fn() => (new Mobil('Avanza', 2022, 45))->isiBahanBakar(0));
coba('Mobil.isiBahanBakar(-5)',           fn() => (new Mobil('Avanza', 2022, 45))->isiBahanBakar(-5));
coba('Isi 30 lalu 30 pada tangki 45 L',   function () {
    $m = new Mobil('Avanza', 2022, 45);
    $m->isiBahanBakar(30);
    $m->isiBahanBakar(30);
});
coba('Mobil dengan merek kosong',         fn() => new Mobil(' ', 2022, 45));
coba('Sepeda tahun 0',                    fn() => new Sepeda('Polygon', 0));
coba('Mobil kapasitas tangki 0',          fn() => new Mobil('Avanza', 2022, 0));
coba('biayaPengisian(-1)',                fn() => TipeBahanBakar::Bensin->biayaPengisian(-1));

echo PHP_EOL, '=== Enum menolak nilai yang tidak terdaftar ===', PHP_EOL;
try {
    TipeBahanBakar::from('hidrogen');
} catch (ValueError $e) {
    echo '  from(\'hidrogen\')    -> ValueError: ', $e->getMessage(), PHP_EOL;
}
var_dump(TipeBahanBakar::tryFrom('hidrogen'));

echo PHP_EOL, '=== umur() tidak pernah negatif ===', PHP_EOL;
$k = new Mobil('Avanza', 2022, 45);
printf('  umur pada 2026 = %d%s', $k->umur(2026), PHP_EOL);
printf('  umur pada 2020 = %d  (tahun sekarang lebih kecil dari tahun pembuatan)%s', $k->umur(2020), PHP_EOL);
