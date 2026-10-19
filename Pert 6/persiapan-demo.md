# Persiapan Demo Pertemuan 6
Jawaban untuk tujuh pertanyaan demo di modul (bagian I). Semua jawaban merujuk ke kode di folder ini.
Pelajari alurnya, lalu coba jalankan sendiri sebelum demo. Modul menegaskan bahwa kode yang tidak dapat dijelaskan tidak dinilai.

## 1. Mengapa `Kendaraan` abstract class, sedangkan `Movable` interface?
Dasarnya adalah **identitas vs kemampuan**. `Kendaraan` menjawab "apa benda ini": Mobil dan Sepeda sama-sama punya `merek`, `tahun`, dan `umur()` yang persis sama, jadi kode itu ditulis sekali di induk. `Movable` menjawab "apa yang bisa dilakukan": kemampuan bergerak bisa dimiliki benda yang tidak sekerabat, dan tiap implementor menggerakkan dirinya dengan cara sendiri sehingga tidak ada kode bersama untuk diwariskan. Uraian lengkap: `keputusan.md`, Keputusan 1.

## 2. Kelas yang mengimplementasikan lebih dari satu interface. Mengapa Java melarang `extends` ganda tetapi mengizinkan `implements` banyak?
`Mobil extends Kendaraan implements Movable, Fuelable` (berkas `java/Mobil.java`). Satu `extends` karena class membawa **field dan kode berbadan**: dua induk bisa bertabrakan (masalah berlian), sehingga kompilator tidak tahu isi mana yang diwariskan. Interface hanya **kontrak**, jadi dua kontrak yang menuntut method bernama sama cukup dipenuhi oleh satu implementasi. Pengecualian pada `default method` dijelaskan di `keputusan.md`, Keputusan 2.

## 3. "Tambahkan `Genset`: butuh bahan bakar tetapi tidak bergerak. Interface mana?"
Hanya **`Fuelable`**, bukan `Movable`. Kelas ini sudah ada di `java/Genset.java` dan `php/abstraksi.php`. Intinya:

```java
public class Genset implements Fuelable {
    // isiBahanBakar(), kapasitasTangki(), tipeBahanBakar() = SOLAR
}
```

Di `Main`, baris `isiPenuh(genset);` langsung berjalan tanpa menyunting `isiPenuh(Fuelable)`. Genset bahkan tidak perlu `extends Kendaraan`, sebab genset bukan kendaraan. Di PHP kelasnya dinamai `Genset` (bukan `Generator`) karena `Generator` sudah dipakai oleh PHP sendiri.

## 4. Mengapa `Sepeda` ditolak saat kompilasi dan bukan saat berjalan? Mana yang lebih baik?
Parameter `isiPenuh(Fuelable kendaraan)` menuntut tipe `Fuelable`, dan `Sepeda` tidak mengimplementasikannya. Kompilator Java memeriksa tipe pada tahap kompilasi:

```text
Main.java:10: error: incompatible types: Sepeda cannot be converted to Fuelable
```

**Lebih baik saat kompilasi**: kesalahan terdeteksi oleh pembuatnya, berlaku pada semua jalur program (tidak bergantung data uji), dan tidak pernah sampai ke pengguna. Di PHP penolakan baru terjadi saat baris dijalankan (`TypeError`). Bukti: `percobaan/01-sepeda-ditolak/`.

## 5. Keuntungan enum `TipeBahanBakar` dibanding tiga konstanta `int`. Buktikan dengan nilai yang tidak terdaftar.
Bukti ada di `percobaan/02-enum-vs-int/`:
- Konstanta int: `hargaInt(99)` lolos dan diam-diam menghasilkan 0.
- Enum: `TipeBahanBakar t = 99;` ditolak kompilator (`incompatible types: int cannot be converted to TipeBahanBakar`), dan `valueOf("HIDROGEN")` melempar `IllegalArgumentException`.

Keuntungan lain: enum membawa data (label, harga) dan perilaku (`biayaPengisian()`, `ramahLingkungan()`), dan `values()` memberi daftar lengkap untuk perulangan.

Di PHP: `TipeBahanBakar::from('hidrogen')` melempar `ValueError`, sedangkan `tryFrom('hidrogen')` mengembalikan `null` (`php/uji-validasi.php`).

## 6. Dua kelas PHP yang memakai trait `Loggable`. Apakah sekerabat? Mengapa trait cocok?
`Mobil` dan `Pesanan` (keduanya di `php/abstraksi.php`). **Tidak sekerabat**: `Mobil` turunan `Kendaraan`, sedangkan `Pesanan` tidak punya induk sama sekali (`main.php` mencetak hasil `get_parent_class()`-nya). Pewarisan tidak bisa dipakai di sini karena tidak ada induk yang masuk akal untuk keduanya. Trait cocok karena `log()` adalah potongan perilaku kecil yang **tidak bergantung pada data** kelas pemakainya (hanya memakai `static::class`).

## 7. Kapan trait menjadi berbahaya? Satu contoh konkret.
Saat trait **diam-diam bergantung pada keadaan kelas pemakai**. Contoh: trait `HitungPajak` menulis `$this->harga * 0.11`. Kelas pemakai yang tidak punya properti `harga` baru gagal saat berjalan, tanpa peringatan kompilator. Bahaya lain: dua trait dengan method `log()` yang bentrok (*fatal error*, diselesaikan dengan `insteadof` dan `as`), dan trait yang terlalu gemuk sehingga menjadi pewarisan jalan belakang. Lengkapnya: `keputusan.md`, Keputusan 4.

---

## Saran riwayat commit
Modul meminta riwayat commit yang menunjukkan proses, bukan satu commit di akhir. Urutan yang dipakai di repositori ini:

| No | Pesan commit | Isi |
|---|---|---|
| 1 | Tambah modul dan starter pertemuan 6 | `Modul-Praktikum-06...docx`, `starter/` |
| 2 | Lengkapi interface, enum, dan abstract class pertemuan 6 (Java) | `Movable`, `Fuelable`, `TipeBahanBakar`, `Kendaraan` |
| 3 | Lengkapi Mobil dan tambah Sepeda serta Genset | `Mobil`, `Sepeda`, `Genset`, `Main`, `UjiValidasi` |
| 4 | Tambah padanan PHP dengan enum dan trait Loggable | `php/` |
| 5 | Tambah latihan Peminjamable dan StatusPinjam | `latihan/` |
| 6 | Tambah class diagram abstraksi | `uml/` |
| 7 | Tambah keputusan, percobaan mandiri, dan tugas rumah | `keputusan.md`, `percobaan/`, `percobaan-mandiri.md`, `refleksi.md`, `persiapan-sesi07.md` |
| 8 | Tambah screenshot coding dan hasil running pertemuan 6 | `IMG/` |
| 9 | Tambah laporan pertemuan 6 dan perbarui README utama | `README.md` (dua berkas) |
