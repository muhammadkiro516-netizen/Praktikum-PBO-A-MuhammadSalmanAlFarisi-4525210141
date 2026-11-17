# Catatan Pengujian (Sesi 11)

## Skenario sesi 10 yang menjadi uji otomatis

| Skenario (skenario-uji.md sesi 10) | Uji Java | Uji PHP |
|---|---|---|
| 1. ISBN tidak terdaftar | `LibraryServiceTest.isbnTidakTerdaftar` | `testSkenario1...` |
| 2. Anggota tidak terdaftar | `anggotaTidakTerdaftar` | `testSkenario2...` |
| 3. ID dan ISBN sama-sama salah (anggota diperiksa dahulu) | `urutanPemeriksaan` | `testSkenario3...` |
| 4. Stok habis | `stokHabis` | `testSkenario4...` |
| 5. Pinjaman ke-4 | `batasPinjam` | `testSkenario5...` |

Tambahan: pinjam yang berhasil mengurangi stok, pinjam yang gagal tidak mengubah stok, dan `cariBuku` yang tidak menemukan apa pun mengembalikan `Optional` kosong atau `null`. Seluruhnya ada 16 uji Java (8 pada `RepositoryTest`, 8 pada `LibraryServiceTest`) dan 17 uji PHP (9 pada `KoleksiBukuTest`, 8 pada `LibraryServiceTest`).

Skenario sesi 10 yang berupa masukan konsol (huruf saat diminta angka, masukan kosong, dan sebagainya) tidak diuji di sini karena antarmuka konsol bukan bagian dari proyek sesi 11.

## Uji yang paling sulit ditulis

Uji skenario 5 (pinjaman ke-4) paling sulit, karena keadaan awalnya harus disiapkan dahulu: anggota harus sudah memegang tiga buku sebelum pinjaman ke-4 bisa dicoba, dan stok buku harus cukup agar yang gagal murni karena batas, bukan karena stok habis. Uji skenario 4 (stok habis) serupa, karena satu-satunya eksemplar harus lebih dahulu dipinjam anggota lain. Pelajarannya: kemudahan menulis uji bergantung pada seberapa mudah objeknya disiapkan. Metode `siapkan` (`@BeforeEach` / `setUp`) membantu karena memberi keadaan awal yang sama dan bersih pada setiap uji.

## Kelemahan yang masih ada

- Uji hanya memeriksa pesan dan data exception, bukan efek sampingnya pada semua kasus gagal. Hanya satu uji yang memastikan pinjam yang gagal tidak mengubah stok.
- Belum ada uji untuk `LaporanBuku` (filter, map, groupingBy) selain verifikasi keluaran `LaporanApp`.
