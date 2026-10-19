# Pertemuan 05 - Asosiasi, Agregasi, dan Komposisi

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Tiga jenis relasi antar class: asosiasi (Dokter merawat Pasien), agregasi (Tim punya Pemain) dan komposisi (Buku punya Bab).

## File

- `Dokter.php` dan `Pasien.php` : asosiasi
- `Tim.php` dan `Pemain.php` : agregasi
- `Buku.php` dan `Bab.php` : komposisi
- `main.php` : program utama

## Perbedaan dengan versi Java

- Asosiasi: Dokter hanya menerima objek Pasien lewat parameter method `merawat()`, tidak disimpan.
- Agregasi: objek Pemain dibuat di luar, lalu dimasukkan ke Tim lewat array. Pemain tetap ada walaupun Tim dihapus.
- Komposisi: objek Bab dibuat langsung di dalam class Buku, jadi hidupnya bergantung ke Buku.
- `List<Pemain>` dan `ArrayList` di Java diganti array PHP.

## Cara menjalankan

```
cd 05
php main.php
```

## Output

Output

```
Dokter Dr. Andi merawat pasien Budi

Tim Garuda memiliki pemain:
- Eko
- Dina

Buku Belajar PHP memiliki bab:
- Pendahuluan
- Isi
- Penutup
```

![Output](output.png)
