# Tugas Pertemuan 3 - Pewarisan

## Identitas Mahasiswa

| Keterangan | Data |
|---|---|
| Nama | Muhammad Salman Al Farisi |
| NIM | 4525210141 |
| Mata Kuliah | Pemrograman Berorientasi Objek A |
| Pertemuan | 3 - Pewarisan (Inheritance) |

## Deskripsi Tugas

Program ini mengimplementasikan hierarki penggajian universitas menggunakan konsep pewarisan pada Java dan PHP. Program memiliki satu kelas induk abstrak dan empat jenis pegawai konkret:

- `PegawaiTetap`
- `PegawaiKontrak`
- `Dosen`
- `PegawaiHarian`

Setiap jenis pegawai mempunyai cara perhitungan gaji yang sesuai dengan karakteristiknya. Kelas induk menyimpan data yang sama, yaitu NIP, nama, dan gaji pokok.

## Konsep yang Diimplementasikan

- Kelas abstrak `Pegawai` sebagai parent class.
- Pewarisan menggunakan `extends` pada Java dan PHP.
- Pemanggilan constructor induk menggunakan `super(...)` dan `parent::__construct(...)`.
- Method overriding pada `hitungGaji()`.
- Pemanggilan method induk menggunakan `super.hitungGaji()` dan `parent::hitungGaji()`.
- Modifier `protected`, `private`, dan `public`.
- Validasi nilai gaji, masa kerja, hari kerja, tunjangan, dan durasi kontrak.
- Komposisi antara `Pegawai` dan `ProfilPembayaran`.

## Struktur Folder

```text
Pewarisan/
|-- README.md
|-- diagram.puml
|-- justifikasi.md
|-- catatan.md
|-- java/
|   |-- Main.java
|   |-- Pegawai.java
|   |-- PegawaiTetap.java
|   |-- PegawaiKontrak.java
|   |-- Dosen.java
|   |-- PegawaiHarian.java
|   `-- ProfilPembayaran.java
`-- php/
    |-- main.php
    `-- Pegawai.php
```

## Perhitungan Gaji

| Jenis Pegawai | Perhitungan |
|---|---|
| Pegawai Tetap | Gaji pokok + tunjangan masa kerja 2% per tahun, maksimal 40% |
| Pegawai Kontrak | Gaji pokok |
| Dosen | Gaji pegawai tetap + tunjangan fungsional |
| Pegawai Harian | Tarif per hari x jumlah hari kerja |

Contoh data pada program menghasilkan total beban gaji sebesar **Rp28.200.000,00**. Gaji Ani sebagai pegawai tetap adalah **Rp7.800.000,00** karena masa kerja 15 tahun menghasilkan tunjangan 30%.

## Cara Menjalankan Java

Buka CMD, lalu masuk ke folder Java:

```cmd
cd /d D:\Praktikum-PBO-A-MuhammadSalmanAlFarisi-4525210141\Praktikum_Pertemuan3\Pewarisan\java
javac *.java
java Main
```

## Cara Menjalankan PHP

Pastikan PHP sudah terpasang, kemudian jalankan:

```cmd
cd /d D:\Praktikum-PBO-A-MuhammadSalmanAlFarisi-4525210141\Praktikum_Pertemuan3\Pewarisan\php
php main.php
```

## Output yang Diharapkan

```text
=== Daftar Gaji ===
  198701012010   TETAP     Ani Lestari          Rp7.800.000,00
  K-2024-007     KONTRAK   Budi Santoso         Rp5.000.000,00
  D-2020-015     DOSEN     Citra Maharani       Rp9.900.000,00
  H-2024-021     HARIAN    Deni Saputra         Rp5.500.000,00

  Total beban gaji: Rp28.200.000,00
```

## Diagram dan Justifikasi

- Diagram kelas PlantUML tersedia di [diagram.puml](diagram.puml).
- Justifikasi hubungan pewarisan dan komposisi tersedia di [justifikasi.md](justifikasi.md).
- Catatan alasan `PegawaiKontrak` tidak melakukan overriding tersedia di [catatan.md](catatan.md).

## Status Pengerjaan

- [x] Minimal empat jenis pegawai.
- [x] Implementasi Java.
- [x] Implementasi PHP.
- [x] Method overriding.
- [x] Validasi data input.
- [x] Relasi komposisi.
- [x] Class diagram PlantUML.
- [x] Justifikasi 300-400 kata.
- [x] Dokumentasi README.
