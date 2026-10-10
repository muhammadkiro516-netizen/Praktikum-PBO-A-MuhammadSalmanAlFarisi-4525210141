<?php
declare(strict_types=1);

/**
 * Langkah 6 — latihan mandiri.
 *
 * Hierarki Notifikasi dengan tiga turunan: Email, SMS, WhatsApp.
 * kirimSemua() TIDAK mengandung pemeriksaan tipe apa pun.
 */

// TODO 1 (selesai): kelas abstrak Notifikasi.
abstract class Notifikasi
{
    public function __construct(protected readonly string $tujuan)
    {
        if (trim($tujuan) === '') {
            throw new InvalidArgumentException('Tujuan notifikasi tidak boleh kosong.');
        }
    }

    /** Kontrak: setiap saluran punya cara sendiri untuk mengirim. */
    abstract public function kirim(string $pesan): void;

    /** Nama saluran diambil dari nama kelas objek yang sebenarnya (late static binding). */
    public function saluran(): string
    {
        return static::class;
    }
}

// TODO 2 (selesai): tiga turunan dengan format pesan yang berbeda.
class Email extends Notifikasi
{
    public function kirim(string $pesan): void
    {
        echo '[', $this->saluran(), '] Kepada : ', $this->tujuan, PHP_EOL;
        echo '        Subjek : Pemberitahuan Perpustakaan', PHP_EOL;
        echo '        Isi    : ', $pesan, PHP_EOL;
    }
}

class SMS extends Notifikasi
{
    private const BATAS_KARAKTER = 160;

    public function kirim(string $pesan): void
    {
        // SMS dibatasi 160 karakter; pesan yang lebih panjang dipotong.
        $isi = strlen($pesan) > self::BATAS_KARAKTER
            ? substr($pesan, 0, self::BATAS_KARAKTER - 3) . '...'
            : $pesan;

        echo '[', $this->saluran(), '] ', $this->tujuan, ' : ', $isi, PHP_EOL;
    }
}

class WhatsApp extends Notifikasi
{
    public function kirim(string $pesan): void
    {
        $nomorInternasional = '+62' . ltrim($this->tujuan, '0');
        echo '[', $this->saluran(), '] ke ', $nomorInternasional, ' : *Pemberitahuan* ', $pesan, PHP_EOL;
    }
}

/**
 * TODO 3 (selesai): kirim pesan ke seluruh notifikasi dalam daftar.
 *
 * Tidak ada instanceof, match, atau switch atas jenis notifikasi.
 * Setiap objek tahu sendiri cara mengirim; fungsi ini hanya meminta "kirim".
 *
 * @param Notifikasi[] $daftar
 */
function kirimSemua(array $daftar, string $pesan): void
{
    foreach ($daftar as $notifikasi) {
        $notifikasi->kirim($pesan);
    }
}

// Uji setelah TODO 1-3 selesai:
echo '=== kirimSemua: Email, SMS, WhatsApp ===', PHP_EOL;
kirimSemua([
    new Email('ani@univpancasila.ac.id'),
    new SMS('081234567890'),
    new WhatsApp('081234567890'),
], 'Buku yang Anda pesan sudah tersedia.');

// Bukti Open-Closed: saluran baru ditambahkan tanpa menyunting kirimSemua().
class Telegram extends Notifikasi
{
    public function kirim(string $pesan): void
    {
        echo '[', $this->saluran(), '] @', $this->tujuan, ' : ', $pesan, PHP_EOL;
    }
}

echo PHP_EOL, '=== Saluran baru (Telegram) tanpa menyunting kirimSemua ===', PHP_EOL;
kirimSemua([new Telegram('ani_pancasila')], 'Terima kasih sudah meminjam.');
