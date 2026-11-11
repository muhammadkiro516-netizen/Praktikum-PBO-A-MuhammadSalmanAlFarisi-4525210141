# Praktikum Sesi 10 — Exception Handling dan Pemrograman Defensif

**Sub-CPMK-P7 — Mahasiswa mampu menerapkan exception handling dan menulis unit test dasar. (P3) — bagian exception.**

**Keterkaitan teori:** Pertemuan 10 — Exception Handling dan Pemrograman Defensif  
**Durasi:** 170 menit (1 SKS praktikum) · **Bobot:** 5% dari nilai praktikum  
**Bahasa:** Java 25 dan PHP 8.4+

> Versi cetak modul ini: [`Modul-Praktikum-10-exception-handling-dan-pemrograman-defensif.docx`](Modul-Praktikum-10-exception-handling-dan-pemrograman-defensif.docx). Kerangka kode: [`starter/`](starter/).

---

## A. Tujuan sesi

1. Membuat hierarki custom exception yang membawa data, bukan hanya pesan.
2. Mengganti percabangan bersarang dengan guard clause.
3. Membuat program tetap berjalan wajar untuk seluruh masukan pengguna yang salah.
4. Menyusun daftar skenario kegagalan sebagai dasar pengujian pada sesi 11.

## B. Alat dan bahan

- Proyek Maven dan Composer hasil sesi 9.

## C. Berkas starter

Salin ke folder tugas Anda; **jangan** menyunting berkas aslinya. Setiap komentar `TODO` harus Anda lengkapi sendiri — kode yang disalin dari sumber lain akan terlihat pada sesi demo.

| Berkas | Keterangan |
|---|---|
| `starter/java/exception/` | Kerangka PerpustakaanException dan empat turunannya, dengan TODO. |
| `starter/java/LibraryServiceKotor.java` | Versi dengan if bersarang empat tingkat — harus Anda refaktor. |
| `starter/php/exceptions.php` | Kerangka exception PHP dengan TODO. |
| `starter/php/LibraryServiceKotor.php` | Padanan PHP yang harus direfaktor. |
| `starter/skenario-uji.md` | Tabel kosong yang harus Anda isi dengan minimal 10 skenario. |

## D. Langkah kerja

Kerjakan berurutan. Jangan melanjutkan ke langkah berikutnya sebelum checkpoint terpenuhi.

### Langkah 1 — Membangun hierarki exception

- Salin starter exception/ ke proyek Maven Anda, pada package id.ac.univpancasila.perpustakaan.exception.
- Lengkapi PerpustakaanException sebagai induk abstrak, lalu empat turunannya:
  • BukuTidakDitemukanException — membawa isbn
  • AnggotaTidakDitemukanException — membawa idAnggota
  • BukuSedangDipinjamException — membawa isbn dan judul
  • BatasPinjamTerlampauiException — membawa idAnggota dan batas
- Setiap exception WAJIB menyimpan datanya sebagai atribut, bukan hanya menyusun pesan teks. Ini yang membedakannya dari exception biasa dan yang akan dipakai pada sesi 13–14.

> **Checkpoint —** Setiap kelas exception punya minimal satu getter yang mengembalikan data penyebab kegagalan.

### Langkah 2 — Merefaktor if bersarang menjadi guard clause

- Buka LibraryServiceKotor. Method pinjam() di dalamnya bersarang empat tingkat.
- Refaktor menjadi guard clause: periksa yang salah lebih dulu dan lempar exception, sehingga logika utama tetap rata di kiri.
- Simpan hasilnya sebagai LibraryService (nama tanpa "Kotor"). JANGAN hapus berkas aslinya — keduanya dipakai saat demo.

> **Checkpoint —** Method pinjam() versi baru tidak punya blok if yang bersarang lebih dari satu tingkat.

### Langkah 3 — Menangani masukan pengguna yang salah

- Tambahkan antarmuka konsol sederhana yang meminta ISBN dan ID anggota dari pengguna.
- Program harus tetap berjalan wajar untuk: huruf saat diminta angka, masukan kosong, ISBN yang tidak ada, ID anggota yang tidak terdaftar, dan angka negatif.
- Program TIDAK BOLEH berhenti karena stack trace. Setiap kegagalan harus menghasilkan pesan yang bisa dibaca pengguna.

**Terminal**

```bash
mvn clean compile
mvn exec:java -Dexec.mainClass=id.ac.univpancasila.perpustakaan.App
```

> **Checkpoint —** Anda dapat mengetik masukan sembarang selama satu menit tanpa program berhenti mendadak.

### Langkah 4 — Menangkap seluruh kegagalan domain dengan satu catch

- Di App, tambahkan satu blok catch (PerpustakaanException e) yang menangani seluruh kegagalan domain sekaligus.
- Di dalamnya, gunakan pemeriksaan tipe untuk mengambil data spesifik dari exception tertentu — misalnya isbn dari BukuTidakDitemukanException.
- Inilah pola yang akan Anda temukan lagi pada sesi 13 (Laravel) dan sesi 14 (Spring) ketika exception diubah menjadi respons HTTP.

> **Checkpoint —** Satu blok catch menangani empat jenis kegagalan berbeda dengan pesan yang tetap spesifik.

### Langkah 5 — try-with-resources dan padanannya di PHP

- Tambahkan fitur ekspor daftar buku ke berkas teks.
- Di Java, gunakan try-with-resources sehingga berkas tertutup otomatis meski terjadi exception.
- Di PHP, tulis penutupan berkas secara manual di blok finally — PHP tidak punya try-with-resources.
- Buktikan: lempar exception di tengah penulisan, lalu pastikan berkas tetap tertutup.

> **Checkpoint —** Berkas tetap tertutup meskipun exception terjadi di tengah proses penulisan.

### Langkah 6 — Menyusun daftar skenario kegagalan

- Lengkapi sesi-10/skenario-uji.md dengan MINIMAL 10 skenario. Untuk setiap skenario tuliskan: masukan, kegagalan yang diharapkan, jenis exception, dan pesan yang ditampilkan.
- Daftar ini menjadi bahan langsung untuk unit test pada sesi 11. Susun dengan serius — Anda sendiri yang akan memakainya pekan depan.

> **Checkpoint —** skenario-uji.md memuat 10 baris lengkap, dan setiap skenario benar-benar sudah Anda coba jalankan.

## E. Latihan mandiri di lab

_Dikerjakan bila langkah kerja selesai lebih awal. Tidak wajib, tetapi menambah nilai pada aspek penerapan konsep._

1. Ubah BukuTidakDitemukanException dari unchecked menjadi checked exception. Catat berapa berkas yang harus ikut disunting. Diskusikan: sepadankah?
2. Buat satu blok catch kosong dengan sengaja, lalu jalankan skenario yang gagal. Amati bahwa kegagalannya hilang tanpa jejak. Kembalikan, dan tuliskan pengalaman itu di skenario-uji.md.

## F. Tugas rumah

1. TUGAS 3 (mata kuliah teori) dikerjakan bersama sesi ini: penanganan kesalahan lengkap pada Sistem Perpustakaan.
2. Tambahkan pencatatan (logging) sederhana: setiap exception yang tertangkap dicatat ke berkas log dengan waktu, jenis, dan data penyebabnya.

## G. Luaran yang dikumpulkan

Seluruh luaran diserahkan melalui repositori Git pribadi Anda, dengan riwayat commit yang menunjukkan proses pengerjaan — bukan satu commit tunggal di akhir.

| Berkas / folder | Keterangan |
|---|---|
| `src/.../exception/` | Empat custom exception yang membawa data, di kedua proyek. |
| `LibraryServiceKotor dan LibraryService` | Versi sebelum dan sesudah refactoring guard clause. |
| `sesi-10/skenario-uji.md` | Minimal 10 skenario kegagalan yang sudah diuji. |
| `Berkas log` | Bukti pencatatan exception. |

## H. Rubrik penilaian sesi ini

| Aspek | Bobot | Kriteria |
|---|---|---|
| Kebenaran fungsional | 35% | Seluruh perintah pada langkah kerja berjalan dan menghasilkan keluaran yang diminta. |
| Penerapan konsep sesi ini | 30% | Konsep yang menjadi Sub-CPMK sesi ini diterapkan dengan tepat, bukan sekadar membuat program berjalan. |
| Keterbacaan dan konvensi | 10% | Penamaan bermakna, format konsisten, komentar seperlunya, riwayat commit wajar. |
| Demo dan pertanyaan lisan | 25% | Mampu menjelaskan setiap baris kode sendiri dan menjawab pertanyaan demo. MENGGUGURKAN: tanpa demo, tugas tidak dinilai. |

## I. Pertanyaan demo

> Pertanyaan berikut akan diajukan saat Anda mendemokan pekerjaan sesi ini. Daftar ini sengaja dibuka agar Anda mempersiapkan **pemahaman**, bukan hafalan. Anda boleh memakai alat bantu apa pun saat mengerjakan — tetapi kode yang tidak dapat Anda jelaskan sendiri tidak dinilai.

1. Tunjukkan satu exception Anda. Data apa yang ia bawa selain pesan teks? Untuk apa data itu nanti?
2. Bandingkan LibraryServiceKotor dan LibraryService. Berapa tingkat kedalaman if pada masing-masing?
3. Saya ketik huruf saat program meminta angka. Tunjukkan apa yang terjadi, lalu tunjuk baris yang menanganinya.
4. Mengapa Anda memilih membuat exception ini unchecked dan bukan checked?
5. Pada blok catch (PerpustakaanException e), bagaimana Anda tetap bisa mengambil isbn yang spesifik?
6. Saya lempar exception di tengah penulisan berkas. Apakah berkasnya tertutup? Buktikan.
7. Apa yang salah dengan menulis catch (Exception e) { } kosong? Apa yang terjadi pada sistem yang memakainya?

## J. Kesalahan yang sering terjadi

| Gejala | Penyebab yang lazim | Cara memperbaiki |
|---|---|---|
| Exception dibuat tetapi tidak menyimpan data | Hanya memanggil super(pesan) tanpa atribut. | Tambahkan atribut privat dan getter. Pesan teks tidak bisa diurai kembali menjadi data. |
| Program berhenti dengan stack trace saat masukan salah | Exception dari pembacaan masukan belum ditangani. | Bungkus pembacaan masukan dengan try-catch, dan minta masukan ulang alih-alih menghentikan program. |
| catch (Exception e) menangkap terlalu banyak | Blok catch terlalu umum sehingga menelan kesalahan pemrograman. | Tangkap tipe yang spesifik. Jika perlu penanganan umum, taruh di lapisan paling luar saja. |
| Guard clause tetap bersarang | Refactoring dilakukan setengah jalan. | Setiap pemeriksaan harus diakhiri throw atau return, sehingga tidak perlu else. |
| PHP: exception tidak tertangkap padahal sudah ada catch | Menangkap Exception padahal yang dilempar turunan Error (mis. TypeError). | Di PHP, Error dan Exception bersaudara di bawah Throwable. Tangkap Throwable bila memang perlu keduanya. |

## Lembar verifikasi demo

Diisi oleh dosen atau asisten pada saat demo. Tugas tanpa lembar terverifikasi tidak dinilai.

|  |  |
|---|---|
| Nama / NIM |  |
| Tanggal demo |  |
| Nilai sesi ini |  |
| Catatan penguji |  |
| Paraf penguji |  |

---

_Modul ini disusun 10 September 2026. Versi teknologi yang dirujuk (Java 25 LTS, PHP 8.4/8.5, Laravel 13, Spring Boot 4.1) diverifikasi pada tanggal tersebut dan perlu diperiksa ulang sebelum semester berjalan._
