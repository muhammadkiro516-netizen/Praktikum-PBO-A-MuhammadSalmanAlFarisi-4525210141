# Tugas Rumah Pertemuan 7: Sistem Presensi Kuliah
**TUGAS 2 (mata kuliah teori)**

Diagram: [uml/presensi.puml](uml/presensi.puml) (render: `presensi.png`, `presensi.svg`). Kode: [java/](java/) dan [php/](php/).

Delapan kelas: `KelasKuliah`, `Pertemuan`, `Presensi`, `Mahasiswa`, `Dosen`, `MataKuliah`, `StatusKehadiran` (enum), dan `RekapPresensi`. Mencakup komposisi, agregasi, asosiasi, dan dependensi, lengkap dengan multiplicity.

Uji yang dipakai untuk setiap relasi: *"Kalau induknya dihapus, apakah bagian ini masih masuk akal?"*

## Justifikasi satu kalimat per relasi

| Relasi | Jenis | Multiplicity | Justifikasi |
|---|---|---|---|
| `KelasKuliah` — `Pertemuan` | **Komposisi** | 1 ke `0..16` | Komposisi dan bukan agregasi, karena "pertemuan ke-3 kelas IF-203-A" tidak punya arti tanpa kelas itu, hanya dibuat lewat `bukaPertemuan()`, dan dibatasi maksimum 16. |
| `Pertemuan` — `Presensi` | **Komposisi** | 1 ke `0..*` | Komposisi dan bukan agregasi, karena sebuah catatan hadir hanya bermakna pada pertemuan tempat ia dicatat dan tidak pernah berpindah ke pertemuan lain. |
| `KelasKuliah` — `Mahasiswa` | **Agregasi** | `*` ke `0..*` | Agregasi dan bukan komposisi, karena mahasiswa sudah ada sebelum kelas dibuat, dapat mengikuti banyak kelas sekaligus, dan tetap ada setelah kelas dibubarkan (`keluarkan()` hanya melepas rujukan). |
| `KelasKuliah` — `MataKuliah` | **Asosiasi** | `*` ke 1 | Asosiasi, karena mata kuliah adalah katalog bersama yang dibuka oleh banyak kelas dan tidak dimiliki satu kelas pun. |
| `KelasKuliah` — `Dosen` | **Asosiasi** | `*` ke 1 | Asosiasi, karena dosen mengampu banyak kelas dan tetap ada bila satu kelas ditutup. |
| `Presensi` — `Mahasiswa` | **Asosiasi** | `*` ke 1 | Asosiasi, karena presensi hanya merujuk pemiliknya; menghapus presensi tidak menghapus mahasiswa. |
| `Presensi` — `StatusKehadiran` | **Asosiasi** | `*` ke 1 | Asosiasi dengan enum, karena status hanyalah nilai dari himpunan tetap dan bukan bagian milik presensi. |
| `RekapPresensi` ⇢ `KelasKuliah` | **Dependensi** | tanpa | Dependensi, karena rekap hanya memakai kelas sebagai parameter saat menghitung dan tidak menyimpannya sebagai field. |

## Aturan bisnis yang diwujudkan di kode

| Aturan | Tempat |
|---|---|
| Satu kelas maksimum 16 pertemuan | `KelasKuliah.bukaPertemuan()` |
| Satu mahasiswa satu catatan per pertemuan | `Pertemuan.catat()` |
| Hanya peserta kelas yang dapat dicatat | `KelasKuliah.catatKehadiran()` |
| Presensi hanya dihitung bila `HADIR`; layak ujian bila minimal 75% | `StatusKehadiran.dihitungHadir()`, `RekapPresensi.layakUjian()` |

## Bukti komposisi dan agregasi (keluaran `java Main`)

```text
=== Agregasi: kelas menaungi mahasiswa ===
  IF-203-A - Pemrograman Berorientasi Objek | pengampu Dr. Rina Wijaya | 3 peserta, 0 pertemuan
  Setelah Cici keluar: IF-203-A - Pemrograman Berorientasi Objek | pengampu Dr. Rina Wijaya | 2 peserta, 0 pertemuan
  Cici tetap ada sebagai objek: 4525210003 Cici Hartono

  Mencoba mengubah koleksi internal dari luar...
    Ditolak - daftar pertemuan tidak bisa diubah dari luar.
    Ditolak - daftar presensi tidak bisa diubah dari luar.
```

Seluruh keluaran lengkap (Java dan PHP) ada di bagian akhir [README.md](../README.md).

## Catatan perbedaan Java dan PHP
- Java: `Presensi` adalah kelas bersarang dengan constructor privat, dan constructor `Pertemuan` package-private, sehingga kompilator menolak pembuatan dari luar. PHP tidak punya padanannya, jadi disiplin itu dijaga lewat anotasi `@internal`.
