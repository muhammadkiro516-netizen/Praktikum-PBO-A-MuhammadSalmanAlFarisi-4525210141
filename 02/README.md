# Pertemuan 02 - Constructor

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Konversi class `Mahasiswa` yang punya tiga constructor (tanpa parameter, 2 parameter, 3 parameter) beserta getter dan setter.

## File

- `Mahasiswa.php` : class Mahasiswa
- `aplikasi.php` : program utama (padanan `Aplikasi.java`)

## Perbedaan dengan versi Java

- PHP tidak mendukung constructor overloading. Tiga constructor di Java digabung jadi satu `__construct()` dengan nilai default, jadi pemanggilan tanpa parameter, 2 parameter, dan 3 parameter tetap bisa dipakai.
- Bagian komentar di `Aplikasi.java` tidak ikut dibawa ke PHP.

## Cara menjalankan

```
cd 02
php aplikasi.php
```

## Output

Output

```
Nama: Belum Diisi
NIM: Belum Diisi
Umur: 0
Nama : Soja Purnamasari
NIM : 4523210104
Umur : 15
Nama: Nenden Nuraini
NIM: 4523210144
Umur: 17
```

![Output](output.png)
