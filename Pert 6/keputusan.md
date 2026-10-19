# Keputusan Rancangan Pertemuan 6
**Materi:** Abstract class, interface, enum, dan trait

Empat catatan keputusan rancangan dan dua pesan kompilator (modul bagian G). Seluruh pesan Java di bawah disalin dari hasil `javac` pada folder `percobaan/`.

---

## Keputusan 1: Kendaraan, Movable, dan Fuelable (Langkah 1)

Pegangan yang dipakai: **abstract class menjawab "apa benda ini"**, sedangkan **interface menjawab "apa yang bisa dilakukannya"**.

| Tipe | Pilihan | Alasan yang berpijak pada "apa benda ini" vs "apa yang bisa dilakukannya" |
|---|---|---|
| `Kendaraan` | **abstract class** | Mobil dan Sepeda *adalah* kendaraan. Keduanya punya `merek` dan `tahun`, serta cara menghitung `umur()` yang persis sama. Kode yang benar-benar sama ditulis sekali di induk. Interface tidak boleh menyimpan data seperti `merek`, jadi tidak cocok untuk tugas ini |
| `Movable` | **interface** | "Bisa bergerak" adalah *kemampuan*, bukan identitas. Kemampuan ini bisa dimiliki benda yang tidak sekerabat: mobil, sepeda, bahkan robot. Setiap implementor menggerakkan dirinya dengan cara sendiri, jadi tidak ada kode bersama yang perlu diwariskan |
| `Fuelable` | **interface** | "Butuh bahan bakar" juga kemampuan. Sepeda bergerak tetapi tidak butuh bahan bakar, sedangkan genset butuh bahan bakar tetapi tidak bergerak. Bila digabung ke `Movable`, sepeda dipaksa punya `isiBahanBakar()` yang tidak masuk akal (*Interface Segregation Principle*) |

Uji cepat yang saya pakai: bila kalimat "X **adalah** Y" masuk akal, pertimbangkan induk (abstract class). Bila yang masuk akal adalah "X **bisa** Y", jadikan interface. "Mobil adalah Kendaraan" benar, "Mobil adalah Movable" terdengar janggal, sedangkan "Mobil bisa bergerak" wajar.

---

## Keputusan 2: Satu `extends`, banyak `implements` (Langkah 3)

`Mobil extends Kendaraan implements Movable, Fuelable` sah. `extends Kendaraan, Mesin` ditolak.

Alasannya adalah **masalah berlian (*diamond problem*)**. Bila dua induk punya method atau field bernama sama dengan isi berbeda, kompilator tidak tahu isi mana yang harus diwariskan. Yang diwariskan oleh class adalah **keadaan (field) dan kode (method berbadan)**, sehingga bentrokannya nyata.

Interface berbeda karena pada dasarnya hanya **kontrak**: tidak punya field instance, dan method-nya (di luar `default`) tidak punya badan. Dua kontrak yang kebetulan menuntut method bernama sama tidak bertabrakan, sebab kelas hanya perlu menulis satu implementasi yang memenuhi keduanya. Bentrokan hanya mungkin pada `default method`, dan di sana Java memaksa kelas pemakai menimpanya sendiri (`Movable.super.ringkasanGerak()` bila ingin memilih salah satu).

---

## Keputusan 3: Mengapa penolakan `isiPenuh(sepeda)` saat kompilasi menguntungkan (Langkah 4)

Sepeda tidak mengimplementasikan `Fuelable`, sehingga `isiPenuh(Fuelable)` tidak bisa menerimanya. Java menolak di tahap kompilasi:

**Pesan kompilator 1 (Java, `percobaan/01-sepeda-ditolak/`):**
```text
Main.java:10: error: incompatible types: Sepeda cannot be converted to Fuelable
        isiPenuh(sepeda);   // <- komentar dihapus: kompilasi harus gagal
                 ^
1 error
```

Mengapa itu menguntungkan:
- **Kesalahan ditemukan oleh pembuatnya, bukan pemakainya.** Program yang salah rancangan tidak pernah sempat dijalankan atau dikirim ke pengguna.
- **Tidak bergantung pada data uji.** Galat saat berjalan hanya muncul bila baris itu *kebetulan* dieksekusi. Galat kompilasi muncul pada semua kemungkinan jalannya program.
- **Murah diperbaiki.** Pesannya menunjuk baris dan penyebabnya (`Sepeda cannot be converted to Fuelable`), sehingga tidak perlu menelusuri jejak galat.
- **Kontrak ikut menjadi dokumentasi.** Tanda tangan `isiPenuh(Fuelable)` sudah menyatakan "hanya untuk yang bisa diisi bahan bakar", dan kompilator yang menegakkannya.

**Pesan kompilator 2 (PHP, `php/main.php`):** PHP tidak punya tahap kompilasi yang memeriksa tipe argumen, jadi penolakannya baru terjadi **saat baris itu dijalankan**, berupa `TypeError`. Bentuk pesannya:
```text
isiPenuh(): Argument #1 ($kendaraan) must be of type Fuelable, Sepeda given, called in <path>\main.php on line 42
```
> Bentuk pesan di atas adalah format standar PHP 8. Jalankan `php main.php` di komputer sendiri, lalu ganti `<path>` dengan hasil asli di terminal Anda dan simpan screenshot-nya sebagai `IMG/hasil-running-php.png`.

Perbandingannya: Java menolak **sebelum** program berjalan, PHP menolak **ketika** baris itu tercapai. Yang pertama lebih baik karena tidak ada jalur program yang lolos tanpa diperiksa.

---

## Keputusan 4: Kapan trait menjadi berbahaya (Langkah 5)

`Loggable` cocok sebagai trait karena `Mobil` dan `Pesanan` **tidak sekerabat** (tidak mungkin keduanya turunan satu induk), tetapi sama-sama butuh `log()`. Itulah penggunaan ulang horizontal.

Trait menjadi berbahaya ketika:

1. **Menyimpan keadaan atau bergantung pada keadaan kelas pemakai.** Contoh konkret: trait `HitungPajak` yang di dalamnya menulis `$this->harga * 0.11`. Trait ini diam-diam mengandaikan setiap pemakainya punya properti `harga`. Kelas yang tidak punya properti itu baru gagal **saat berjalan**, dan kompilator tidak memberi tahu. Kontrak yang tersembunyi seperti ini seharusnya ditulis eksplisit lewat interface.
2. **Method bernama sama berasal dari dua trait.** Contoh: `trait Loggable { function log() {...} }` dan `trait Auditable { function log() {...} }` dipakai bersama pada satu kelas. PHP berhenti dengan *fatal error* karena bentrok, dan harus diselesaikan manual dengan `insteadof` dan `as`. Semakin banyak trait, semakin sulit menebak method mana yang sebenarnya berjalan.
3. **Dipakai sebagai pewarisan jalan belakang.** Trait yang berisi puluhan method dan banyak logika bisnis membuat kelas pemakainya seolah-olah mewarisi banyak induk (padahal Java dan PHP sengaja melarang itu). Hasilnya kelas gemuk yang sulit diuji karena perilakunya tersebar di banyak berkas.
4. **Tidak membentuk tipe.** Trait tidak bisa dipakai sebagai tipe parameter. Fungsi tidak bisa menulis `function f(Loggable $x)`, sehingga polimorfisme tidak tersedia. Bila fungsi lain perlu mengetahui "objek ini bisa log", pasangkan trait dengan interface (misalnya `interface Loggable` plus trait penyedia implementasinya).

Aturan praktis: pakai trait untuk **potongan perilaku kecil yang tidak bergantung pada data kelas pemakai**. Bila perilakunya perlu dijadikan tipe, gunakan interface.

---

## Catatan tambahan (hasil percobaan, bukan bagian dari empat keputusan)

**Enum vs konstanta int** (pertanyaan demo 5, `percobaan/02-enum-vs-int/`):
```text
hargaInt(99) = 0.0   <- lolos tanpa peringatan apa pun
```
```text
SalahTipe.java:4: error: incompatible types: int cannot be converted to TipeBahanBakar
        TipeBahanBakar t = 99;
                           ^
1 error
```

**Interface gemuk** (latihan mandiri E2, `percobaan/03-interface-gemuk/gemuk/`):
```text
Lampu.java:2: error: Lampu is not abstract and does not override abstract method perbaruiFirmware() in PerangkatSerbaBisa
public class Lampu implements PerangkatSerbaBisa {
       ^
1 error
```
