# LAPORAN PRAKTIKUM PEMROGRAMAN BERBASIS OBJEK

| Informasi Praktikan | Keterangan |
| :--- | :--- |
| **Nama** | Muhammad Salman Al Farisi |
| **NPM** | 4525210141 |
| **Kelas** | A |
| **Mata Kuliah** | Pemrograman Berbasis Objek (PBO) |
| **Pertemuan** | 10 - Exception Handling dan Pemrograman Defensif |
| **Tanggal** | 26 Oktober 2026 |

---

## 1. Implementasi Java

Topik sesi ini: **exception handling** dan **pemrograman defensif**. Ada dua teknik utama yang dikerjakan:

1. **Hierarki exception domain** — exception khusus yang membawa data penyebab kegagalan.
2. **Guard clause** — membalik kondisi bersarang menjadi pemeriksaan awal yang keluar lebih cepat, sehingga logika utama tetap rata di kiri.

Studi kasus: sistem peminjaman perpustakaan sederhana dengan aturan bisnis (batas pinjam, stok, keberadaan anggota/buku).

---

### 1.1. File: `java/exception/PerpustakaanException.java`

**Penjelasan Kode:**
> Kelas abstrak induk seluruh exception domain perpustakaan. Diturunkan dari `RuntimeException` (unchecked) agar kompilator tidak memaksa setiap pemanggil mendeklarasikan `throws`, sesuai pedoman *Effective Java* Item 72. Menyediakan dua constructor: satu dengan pesan saja, satu dengan pesan dan *cause* (untuk exception chaining). Manfaat memiliki induk bersama: pemanggil dapat menangkap seluruh kegagalan domain sekaligus dengan **satu blok `catch (PerpustakaanException e)`** tanpa ikut menelan `NullPointerException` atau kesalahan pemrograman lainnya.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter, TODO belum dikerjakan)*:
![SS Before PerpustakaanException.java](IMG/before-PerpustakaanException-java.png)

* **After** *(Kondisi akhir — implementasi selesai)*:
![SS After PerpustakaanException.java](IMG/PerpustakaanException-java.png)

---

### 1.2. File: `java/exception/BukuTidakDitemukanException.java`

**Penjelasan Kode:**
> Turunan `PerpustakaanException` yang membawa data `isbn`. Pesan exception sudah menyertakan nilai ISBN sehingga pengguna maupun log langsung tahu ISBN mana yang bermasalah. Getter `getIsbn()` disediakan agar pemanggil dapat mengambil data secara terprogram — bukan mengurai string pesan.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter, TODO belum dikerjakan)*:
![SS Before BukuTidakDitemukanException.java](IMG/before-BukuTidakDitemukanException-java.png)

* **After** *(Kondisi akhir — implementasi selesai)*:
![SS After BukuTidakDitemukanException.java](IMG/BukuTidakDitemukanException-java.png)

---

### 1.3. File: `java/exception/AnggotaTidakDitemukanException.java`

**Penjelasan Kode:**
> Pola yang sama dengan `BukuTidakDitemukanException`, membawa `idAnggota`. Getter `getIdAnggota()` memungkinkan respons HTTP 404 yang terstruktur di sesi selanjutnya.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter, TODO belum dikerjakan)*:
![SS Before AnggotaTidakDitemukanException.java](IMG/before-AnggotaTidakDitemukanException-java.png)

* **After** *(Kondisi akhir — implementasi selesai)*:
![SS After AnggotaTidakDitemukanException.java](IMG/AnggotaTidakDitemukanException-java.png)

---

### 1.4. File: `java/exception/BukuSedangDipinjamException.java`

**Penjelasan Kode:**
> Exception ini membawa **dua** data: `isbn` dan `judul`. `judul` disertakan agar pesan untuk pengguna bermakna ("Semua eksemplar 'Effective Java' sedang dipinjam"), sedangkan `isbn` disertakan agar sistem dapat memprosesnya lebih lanjut. Ini contoh bahwa exception bukan sekadar teks — ia adalah objek dengan data.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter, TODO belum dikerjakan)*:
![SS Before BukuSedangDipinjamException.java](IMG/before-BukuSedangDipinjamException-java.png)

* **After** *(Kondisi akhir — implementasi selesai)*:
![SS After BukuSedangDipinjamException.java](IMG/BukuSedangDipinjamException-java.png)

---

### 1.5. File: `java/exception/BatasPinjamTerlampauiException.java`

**Penjelasan Kode:**
> Membawa `idAnggota` dan `batas`. Menyertakan `batas` di dalam exception berarti pemanggil tidak perlu mengetahui konstanta `BATAS_PINJAM` sendiri untuk menyusun pesan error yang informatif.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter, TODO belum dikerjakan)*:
![SS Before BatasPinjamTerlampauiException.java](IMG/before-BatasPinjamTerlampauiException-java.png)

* **After** *(Kondisi akhir — implementasi selesai)*:
![SS After BatasPinjamTerlampauiException.java](IMG/BatasPinjamTerlampauiException-java.png)

---

### 1.6. File: `java/LibraryServiceKotor.java` — Sebelum Refaktor

**Penjelasan Kode:**
> Ini adalah **bahan refaktor** — sengaja dibiarkan apa adanya dari starter. Method `pinjam()` bersarang **empat tingkat**: anggota ada → buku ada → stok > 0 → pinjaman < batas. Masalahnya: semua cabang kegagalan hanya mengembalikan `false` tanpa penjelasan. Pemanggil tidak bisa tahu *mengapa* gagal. Berkas ini tidak dihapus agar perbandingan "sebelum" dan "sesudah" bisa dilihat langsung.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — bersarang empat tingkat, semua gagal dengan `return false`)*:
![SS Before LibraryServiceKotor.java](IMG/before-LibraryServiceKotor-java.png)

* **After** *(Tidak diubah — sengaja dipertahankan untuk demo perbandingan)*:
![SS After LibraryServiceKotor.java](IMG/LibraryServiceKotor-java.png)

---

### 1.7. File: `java/LibraryService.java` — Setelah Refaktor

**Penjelasan Kode:**
> Versi refaktor dengan **guard clause**: setiap kondisi kegagalan diperiksa di awal dan langsung melempar exception spesifik. Logika utama (mengurangi stok, menambah hitungan pinjaman) hanya ada di baris terakhir, tidak bersarang. Tambahan fitur: `eksporDaftarBuku()` memakai **try-with-resources** (`BufferedWriter` ditutup otomatis meski exception terjadi di tengah), dan `log()` mencatat exception ke file log dengan timestamp.

Perbandingan `pinjam()` sebelum dan sesudah:

| `LibraryServiceKotor.pinjam()` | `LibraryService.pinjam()` |
|---|---|
| `boolean` — pemanggil tidak tahu mengapa gagal | `void` — exception membawa alasan lengkap |
| Bersarang 4 tingkat | Datar (guard clause) |
| Tidak ada pencatatan | Exception dicatat ke log |

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter kosong)*:
![SS Before LibraryService.java](IMG/before-LibraryService-java.png)

* **After** *(Kondisi akhir — guard clause, try-with-resources, logging)*:
![SS After LibraryService.java](IMG/LibraryService-java.png)

---

### 1.8. File: `java/Main.java`

**Penjelasan Kode:**
> Program konsol yang menggabungkan semua konsep:
> - **Demo otomatis**: 7 skenario dijalankan berurutan, menunjukkan semua jenis exception dan satu kasus berhasil.
> - **Satu blok `catch (PerpustakaanException e)`**: menangkap semua kegagalan domain; lalu memeriksa jenis spesifiknya dengan *pattern matching* `instanceof` (Java 16+) untuk mengambil data getter.
> - **try-with-resources** pada ekspor: `BufferedWriter` ditutup otomatis.
> - **Mode interaktif**: program terus berjalan meski masukan salah — tidak crash.
> - **Logging**: setiap exception dicatat ke `perpustakaan.log` dengan timestamp.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter kosong)*:
![SS Before Main.java](IMG/before-Main-java.png)

* **After** *(Kondisi akhir — semua skenario demo berhasil)*:
![SS After Main.java (1/2)](IMG/Main-java-1.png)
![SS After Main.java (2/2)](IMG/Main-java-2.png)

### Output Program Java
![Output Java](IMG/hasil-running-java.png)

**Output yang diharapkan:**
```
=== Sistem Perpustakaan — Sesi 10: Exception Handling ===
Ketik masukan sembarang untuk menguji ketahanan program.

--- Demo otomatis: skenario kegagalan ---
  [OK] A001 meminjam ISBN 978-0-13-468599-1
  [!] AnggotaTidakDitemukanException              -> Anggota dengan ID TIDAK_ADA tidak ditemukan.
  [!] BukuTidakDitemukanException                 -> Buku dengan ISBN ISBN-TIDAK-ADA tidak ditemukan.
  [OK] A001 meminjam ISBN 978-0-13-110362-7
  [!] BukuSedangDipinjamException                 -> Semua eksemplar "The C Programming Language" (ISBN 978-0-13-110362-7) sedang dipinjam.
  [OK] A001 meminjam ISBN 978-0-20-163361-5
  [!] BatasPinjamTerlampauiException              -> Anggota A001 telah mencapai batas pinjam maksimum (3 koleksi).

--- Demo ekspor daftar buku (try-with-resources) ---
  Ekspor berhasil -> daftar-buku.txt

--- Mode interaktif (ketik 'keluar' untuk berhenti) ---
ISBN  : keluar
Program selesai.
```

**Cara menjalankan:**
```cmd
cd "Pert 10\java"
javac -d out exception/*.java LibraryServiceKotor.java LibraryService.java Main.java
java -cp out Main
```

---

## 2. Implementasi PHP

PHP tidak mengenal *checked exception*; semua exception bersifat unchecked secara alami. Pola guard clause dan hierarki exception tetap sama, hanya sintaksnya berbeda. PHP 8.1 memperkenalkan **constructor property promotion** dan **`readonly`** yang membuat kode exception lebih ringkas.

---

### 2.1. File: `php/exceptions.php`

**Penjelasan Kode:**
> Hierarki exception domain perpustakaan lengkap dalam satu file. `PerpustakaanException` adalah abstract class yang menurunkan `RuntimeException` PHP. Empat kelas turunan memakai **constructor property promotion** dengan modifier `private readonly` sehingga properti langsung terdefinisi, tersimpan, dan tidak bisa diubah setelah konstruksi — tanpa perlu pendeklarasian terpisah. Semua kelas ditandai `final` agar tidak diturunkan lebih lanjut tanpa alasan jelas.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter, TODO belum dikerjakan)*:
![SS Before exceptions.php](IMG/before-exceptions-php.png)

* **After** *(Kondisi akhir — implementasi selesai)*:
![SS After exceptions.php](IMG/exceptions-php.png)

---

### 2.2. File: `php/LibraryServiceKotor.php`

**Penjelasan Kode:**
> Salinan langsung dari starter — bahan refaktor. Pola bersarang empat tingkat identik dengan versi Java. Satu penyesuaian: `public const BATAS_PINJAM = 3` tanpa deklarasi tipe karena PHP < 8.3 tidak mendukung typed class constants di semua versi; tipe ditulis tanpa annotation agar kompatibel. Berkas ini tidak diubah, hanya disimpan sebagai perbandingan.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — kode starter)*:
![SS Before LibraryServiceKotor.php](IMG/before-LibraryServiceKotor-php.png)

* **After** *(Tidak diubah — dipertahankan untuk perbandingan)*:
![SS After LibraryServiceKotor.php](IMG/LibraryServiceKotor-php.png)

---

### 2.3. File: `php/LibraryService.php`

**Penjelasan Kode:**
> Versi refaktor PHP dengan guard clause yang identik secara struktur dengan Java. `pinjam()` bertipe `void` dan melempar exception — bukan mengembalikan `bool`. PHP tidak memiliki **try-with-resources**, jadi `eksporDaftarBuku()` memakai **try-finally**: `fclose($fp)` di blok `finally` memastikan file handle ditutup meski exception terjadi di tengah penulisan. `log()` memakai `file_put_contents(..., FILE_APPEND)` yang lebih ringkas dari Java.

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — file kosong, belum ada)*:
![SS Before LibraryService.php](IMG/before-LibraryService-php.png)

* **After** *(Kondisi akhir — guard clause, try-finally, logging)*:
![SS After LibraryService.php](IMG/LibraryService-php.png)

---

### 2.4. File: `php/main.php`

**Penjelasan Kode:**
> Demo PHP yang sejajar dengan `Main.java`: 7 skenario otomatis, demo ekspor try-finally, dan blok tangkap satu `PerpustakaanException` dengan pemeriksaan `instanceof` untuk data spesifik. PHP tidak punya *pattern matching* seperti Java 16+, jadi `instanceof` biasa tetap digunakan. Perbedaan: tidak ada mode interaktif CLI (PHP tidak seinteraktif Java di lingkungan Windows tanpa `readline`).

**Bukti Eksekusi (Screenshot):**
* **Before** *(Kondisi awal — file kosong, belum ada)*:
![SS Before main.php](IMG/before-main-php.png)

* **After** *(Kondisi akhir — semua skenario demo berhasil)*:
![SS After main.php](IMG/main-php.png)

### Output Program PHP
![Output PHP](IMG/hasil-running-php.png)

**Output yang diharapkan:**
```
=== Sistem Perpustakaan — Sesi 10: Exception Handling (PHP) ===

--- Demo otomatis: skenario kegagalan ---
  [OK] A001 meminjam ISBN 978-0-13-468599-1
  [!] AnggotaTidakDitemukanException              -> Anggota dengan ID TIDAK_ADA tidak ditemukan.
  [!] BukuTidakDitemukanException                 -> Buku dengan ISBN ISBN-TIDAK-ADA tidak ditemukan.
  [OK] A001 meminjam ISBN 978-0-13-110362-7
  [!] BukuSedangDipinjamException                 -> Semua eksemplar "The C Programming Language" (ISBN 978-0-13-110362-7) sedang dipinjam.
  [OK] A001 meminjam ISBN 978-0-20-163361-5
  [!] BatasPinjamTerlampauiException              -> Anggota A001 telah mencapai batas pinjam maksimum (3 koleksi).

--- Demo ekspor daftar buku (try-finally) ---
  Ekspor berhasil -> daftar-buku.txt

--- Tangkap satu blok PerpustakaanException, ambil data spesifik ---
  [!] Buku tidak ada: ISBN=ISBN-X
  [!] Anggota tidak ada: ID=ID-X

Program PHP selesai. Log exception: perpustakaan.log
```

**Cara menjalankan:**
```cmd
cd "Pert 10\php"
php main.php
```

---

## 3. Kesimpulan

> Exception handling yang baik bukan soal mencegah program crash — itu terlalu mudah. Yang sulit adalah membuat exception **membawa cukup informasi** agar pemanggil (dan pengembang) tahu *apa yang salah*, *di mana*, dan *data apa* yang terlibat.
>
> Dua teknik utama yang dikerjakan di pertemuan ini:
>
> | Teknik | Masalah yang diatasi |
> |---|---|
> | **Hierarki exception domain** | `return false` tidak menceritakan mengapa gagal |
> | **Guard clause** | Bersarang dalam menyulitkan pembacaan dan perluasan |
> | **try-with-resources / try-finally** | Resource tidak tertutup bila exception terjadi di tengah |
> | **Logging** | Kegagalan hilang tanpa jejak |
>
> ### Perbandingan Java vs PHP untuk Exception Handling
>
> | Aspek | Java | PHP |
> |---|---|---|
> | Jenis exception | **Checked** (`Exception`) dan **Unchecked** (`RuntimeException`) | Semua unchecked (tidak ada perbedaan) |
> | Paksaan kompilator | `throws` wajib dideklarasikan untuk checked | Tidak ada; `@throws` di PHPDoc hanya dokumentasi |
> | Constructor ringkas | Harus deklarasi field + assignment manual | **Constructor property promotion** + `readonly` |
> | Penutupan resource | **try-with-resources** (otomatis) | **try-finally** (manual di `finally`) |
> | Pattern matching catch | `instanceof` + binding (`BukuTidakDitemukanException bte`) Java 16+ | `instanceof` biasa (PHP 8.0+) |
> | Chaining exception | `new Exception("...", $cause)` atau constructor dua argumen | Argumen `$previous` di constructor `Throwable` |
>
> Kesimpulan praktis: **unchecked exception** (turunan `RuntimeException`) lebih cocok untuk kegagalan domain bisnis yang seharusnya sudah dicegah sebelum memanggil layanan. **Checked exception** (`IOException`) lebih cocok untuk kondisi eksternal yang memang harus diantisipasi pemanggil. PHP tidak memaksa pemisahan ini — tanggung jawab ada pada disiplin pengembang dan dokumentasi `@throws`.
>
> Cara menjalankan:
> ```cmd
> cd "Pert 10\java"
> javac -d out exception/*.java LibraryServiceKotor.java LibraryService.java Main.java
> java -cp out Main
>
> cd "..\php"
> php main.php
> ```
