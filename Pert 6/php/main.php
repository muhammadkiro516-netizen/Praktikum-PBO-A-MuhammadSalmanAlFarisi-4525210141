<?php
declare(strict_types=1);

require_once __DIR__ . '/abstraksi.php';

date_default_timezone_set('Asia/Jakarta');

/** Tidak peduli kelas konkretnya, hanya peduli kontraknya. */
function isiPenuh(Fuelable $kendaraan): void
{
    $kendaraan->isiBahanBakar($kendaraan->kapasitasTangki());
    $biaya = $kendaraan->tipeBahanBakar()->biayaPengisian($kendaraan->kapasitasTangki());
    printf('  Diisi penuh %s - biaya Rp%s%s',
        $kendaraan->tipeBahanBakar()->label(),
        number_format($biaya, 0, ',', '.'), PHP_EOL);
}

$mobil  = new Mobil('Toyota Avanza', 2022, 45);
$sepeda = new Sepeda('Polygon Heist', 2023);
$genset = new Genset('Genset Honda', 20);

// Checkpoint Langkah 3: satu objek Mobil memenuhi tiga tipe sekaligus.
echo '=== Satu objek Mobil, tiga tipe ===', PHP_EOL;
printf('  Kendaraan : %s, umur %d tahun%s', $mobil, $mobil->umur(2026), PHP_EOL);
printf('  Movable   : %s%s', $mobil instanceof Movable ? 'ya' : 'tidak', PHP_EOL);
printf('  Fuelable  : %s%s', $mobil instanceof Fuelable ? 'ya' : 'tidak', PHP_EOL);

echo PHP_EOL, '=== Semua Movable ===', PHP_EOL;
// Langkah 4: $sepeda ditambahkan ke daftar. Perulangan tidak diubah.
foreach ([$mobil, $sepeda] as $m) {
    $m->bergerak();
    printf('    kecepatan maksimum %.0f km/jam%s', $m->kecepatanMaksimum(), PHP_EOL);
}

echo PHP_EOL, '=== Hanya yang Fuelable ===', PHP_EOL;
isiPenuh($mobil);
isiPenuh($genset);   // pertanyaan demo 3

// Langkah 4: percobaan isiPenuh($sepeda) memang gagal. Dibungkus try/catch
// supaya pesan TypeError-nya bisa dicetak tanpa menghentikan program.
try {
    isiPenuh($sepeda);
} catch (TypeError $e) {
    echo '  DITOLAK saat berjalan: ', $e->getMessage(), PHP_EOL;
}

echo PHP_EOL, '=== Enum punya perilaku ===', PHP_EOL;
foreach (TipeBahanBakar::cases() as $t) {
    printf('  %-8s ramah lingkungan? %-5s  biaya 10 satuan: Rp%s%s',
        $t->label(),
        $t->ramahLingkungan() ? 'ya' : 'tidak',
        number_format($t->biayaPengisian(10), 0, ',', '.'), PHP_EOL);
}

echo PHP_EOL, '=== Trait dipakai kelas yang tidak sekerabat ===', PHP_EOL;
$mobil->log('servis berkala selesai');
(new Pesanan())->log('pesanan #1042 dibuat');
printf('  Mobil dan Pesanan sekerabat? %s%s',
    get_parent_class(Pesanan::class) === false ? 'tidak (Pesanan tidak punya induk)' : 'ya', PHP_EOL);
