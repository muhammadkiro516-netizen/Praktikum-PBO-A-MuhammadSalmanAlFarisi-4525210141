# Tugas Pertemuan 3 - Pewarisan (Inheritance)

## Identitas Mahasiswa

| Keterangan | Data |
|---|---|
| Nama | Muhammad Salman Al Farisi |
| NIM | 4525210141 |
| Mata Kuliah | Pemrograman Berorientasi Objek A |
| Pertemuan | 3 - Pewarisan (Inheritance) |

## Foto Mahasiswa

Foto belum disertakan karena belum ada file foto yang tersedia di folder tugas.
Simpan foto dengan nama `foto-muhammad-salman-al-farisi.jpg` di folder
`Pewarisan/foto/`, lalu tambahkan gambar dengan format berikut:

```markdown
![Foto Muhammad Salman Al Farisi](foto/foto-muhammad-salman-al-farisi.jpg)
```

## Gambaran Program

Program ini dibuat untuk mensimulasikan perhitungan gaji beberapa jenis pegawai
di lingkungan universitas. Data pegawai yang sama ditempatkan pada kelas induk
`Pegawai`, kemudian aturan gaji yang berbeda diletakkan pada kelas turunannya.
Dengan cara ini, setiap kelas tidak perlu menyimpan ulang data NIP, nama, dan
gaji pokok.

Implementasinya dibuat dalam dua bahasa, yaitu Java dan PHP. Keduanya memakai
struktur dan contoh data yang sama supaya hasilnya dapat dibandingkan dengan
mudah. Jenis pegawai yang digunakan adalah:

- `PegawaiTetap`
- `PegawaiKontrak`
- `Dosen`
- `PegawaiHarian`

Setiap jenis pegawai mempunyai aturan penggajian sendiri, tetapi semuanya tetap
dapat diproses melalui tipe `Pegawai`.

## Konsep yang Dipakai

- Kelas abstrak `Pegawai` sebagai parent class.
- Pewarisan menggunakan `extends` pada Java dan PHP.
- Constructor turunan tetap memanggil constructor induk.
- Overriding pada method `hitungGaji()`.
- Pemakaian `super.hitungGaji()` di Java dan `parent::hitungGaji()` di PHP.
- Penggunaan modifier `protected`, `private`, dan `public`.
- Validasi agar nilai gaji dan data pendukung tidak negatif.
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

Dengan data contoh yang tersedia, total beban gaji adalah
**Rp28.200.000,00**. Gaji Ani menjadi **Rp7.800.000,00** karena masa kerjanya
15 tahun memberikan tunjangan sebesar 30% dari gaji pokok.

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

## Dokumen Pendukung

- Diagram kelas PlantUML tersedia di [diagram.puml](diagram.puml).
- Justifikasi hubungan pewarisan dan komposisi tersedia di [justifikasi.md](justifikasi.md).
- Catatan alasan `PegawaiKontrak` tidak melakukan overriding tersedia di [catatan.md](catatan.md).

Dokumen-dokumen tersebut disertakan agar alasan pemilihan pewarisan dan
komposisi dapat dilihat bersama dengan source code, bukan hanya dari hasil
output program.

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
