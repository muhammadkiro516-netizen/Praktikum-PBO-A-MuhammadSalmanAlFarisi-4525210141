# Hasil Latihan Mandiri di Lab
**Materi:** Abstract class, interface, enum, dan trait (Pertemuan 6)

Seluruh percobaan memakai salinan kode di folder `percobaan/`, sehingga berkas di `java/` hanya berubah pada bagian yang memang tugas (lihat catatan E1).

---

## E1. Default method pada `Movable`, lalu di-override di salah satu implementor

**Yang dikerjakan:** `Movable` sudah punya `default String ringkasanGerak()` (TODO 1 Langkah 1). `Sepeda` meng-override-nya, sedangkan `Mobil` memakai versi bawaan.

```java
// Movable.java
default String ringkasanGerak() {
    return String.format("kecepatan maksimum %.0f km/jam", kecepatanMaksimum());
}

// Sepeda.java
@Override public String ringkasanGerak() {
    return Movable.super.ringkasanGerak() + " (bergantung tenaga pengayuh)";
}
```

**Hasil** (`java Main`, bagian "Semua Movable"):
```text
  Toyota Avanza melaju di jalan raya
    kecepatan maksimum 180 km/jam
  Polygon Heist dikayuh di jalur sepeda
    kecepatan maksimum 40 km/jam (bergantung tenaga pengayuh)
```
`Movable.super.ringkasanGerak()` memanggil versi bawaan milik interface, lalu `Sepeda` menambah keterangannya sendiri. Perulangan di `Main` tidak berubah: yang menentukan teks mana yang tercetak adalah **tipe objek** (dynamic dispatch, sama seperti pertemuan 5).

**Kapan default method berguna:**
- **Mengembangkan interface lama tanpa mematahkan implementornya.** Method baru diberi isi bawaan sehingga kelas yang sudah ada tetap bisa dikompilasi. Inilah alasan Java 8 menambahkannya (contohnya `forEach` pada `Iterable`).
- **Perilaku yang murni turunan dari method abstrak.** `ringkasanGerak()` hanya menyusun teks dari `kecepatanMaksimum()`, jadi menyalinnya ke setiap kelas hanya menghasilkan kode kembar.

**Kapan default method menyesatkan:**
- **Implementor mewarisi perilaku tanpa sadar.** Bila `Sepeda` tidak meng-override, ringkasannya "kecepatan maksimum 40 km/jam" terdengar pasti, padahal kecepatan sepeda bergantung pada pengayuhnya. Kontrak yang awalnya hanya "wajib mengisi dua method" diam-diam membawa perilaku ketiga.
- **Bentrok dua interface.** Bila dua interface punya default method bernama sama, kelas pemakai wajib menimpanya sendiri, kalau tidak kompilasi gagal.
- **Menjadi abstract class yang menyamar.** Interface dengan banyak default method yang berisi logika sudah bukan kontrak lagi. Bila logikanya butuh data bersama, tempatnya di abstract class (seperti `Kendaraan`).

---

## E2. Interface gemuk (delapan method), lalu dipecah

**Percobaan** (`percobaan/03-interface-gemuk/gemuk/`): `PerangkatSerbaBisa` punya delapan method (`hidupkan`, `matikan`, `cetak`, `pindai`, `kirimFaks`, `sambungkanWifi`, `aturVolume`, `perbaruiFirmware`). `Lampu` hanya butuh dua di antaranya (`hidupkan` dan `matikan`), tetapi terpaksa `implements` seluruhnya.

**Hasil kompilasi:**
```text
Lampu.java:2: error: Lampu is not abstract and does not override abstract method perbaruiFirmware() in PerangkatSerbaBisa
public class Lampu implements PerangkatSerbaBisa {
       ^
1 error
```

**Yang terasa sebagai masalah:**
- `javac` hanya melaporkan satu method yang belum ada pada satu waktu. Enam method lainnya (`cetak`, `pindai`, `kirimFaks`, `sambungkanWifi`, `aturVolume`, `perbaruiFirmware`) harus ditambahkan satu per satu.
- Satu-satunya jalan agar lolos adalah menulis enam method kosong atau yang melempar `UnsupportedOperationException`. Itu **berbohong kepada pemanggil**: `Lampu` mengaku bisa `cetak()` padahal tidak.
- Bila interface kelak menambah method kesembilan, **semua** implementor, termasuk yang tidak peduli, ikut rusak.

**Solusi** (`percobaan/03-interface-gemuk/pecah/`): dipecah menjadi `Switchable` (`hidupkan`, `matikan`), `Printable` (`cetak`), dan `Scannable` (`pindai`). `Lampu` hanya `implements Switchable`, sedangkan `Printer` mengambil `Switchable` dan `Printable`.

```text
Lampu menyala
Printer siap
Mencetak: Laporan praktikum 6
```
Fungsi `nyalakanSemua(Switchable...)` menerima `Lampu` maupun `Printer` tanpa tahu kelas konkretnya. Ini penerapan *Interface Segregation Principle* yang sama dengan pemisahan `Movable` dan `Fuelable`.

---

## Tambahan: bukti untuk pertanyaan demo 5 (enum vs konstanta int)

**Percobaan** (`percobaan/02-enum-vs-int/`):

```text
=== A. Konstanta int ===
  hargaInt(1)  = 12000.0
  hargaInt(99) = 0.0   <- lolos tanpa peringatan apa pun

=== B. Enum ===
  valueOf("SOLAR")     = SOLAR
  valueOf("HIDROGEN")  ditolak saat berjalan: No enum constant TipeBahanBakar.HIDROGEN
```
```text
SalahTipe.java:4: error: incompatible types: int cannot be converted to TipeBahanBakar
        TipeBahanBakar t = 99;
                           ^
1 error
```

Pada konstanta `int`, angka 99 tidak dikenal tetapi diam-diam diperlakukan sebagai harga 0. Pada enum, `TipeBahanBakar t = 99` ditolak oleh kompilator, dan teks yang tidak terdaftar ditolak oleh `valueOf`. Enum juga bisa membawa label, harga, dan method (`biayaPengisian`, `ramahLingkungan`) yang tidak dimiliki angka.
