# Tugas 1 PBO - Convert Java ke PHP

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Tugas konversi source code Java pertemuan 01 sampai 06 ke bahasa PHP.

Source code asli (Java): https://github.com/adiwp/pbo20192020II/tree/master/gasal20242025

## Daftar Isi

| Folder | Materi |
|---|---|
| [01](01) | Class dan Object |
| [02](02) | Constructor |
| [03](03) | Inheritance |
| [04](04) | Polymorphism |
| [05](05) | Asosiasi, Agregasi, Komposisi |
| [06](06) | Abstract Class dan Interface |

## Cara Menjalankan

Pakai PHP CLI (dites di PHP 8.3). Masuk ke foldernya lalu jalankan file utamanya, contoh:

```
cd 01
php main.php
```

## Perbedaan Java dan PHP yang Dipakai di Tugas Ini

- `this.nama` jadi `$this->nama`, pemanggilan method pakai `->`.
- Constructor namanya `__construct()`, dan `super()` jadi `parent::__construct()`.
- Penggabungan string pakai `.` (titik), bukan `+`.
- `System.out.println()` diganti `echo` dengan `PHP_EOL` untuk pindah baris.
- PHP tidak butuh method `main()`, file utamanya langsung dijalankan.
- Constructor overloading tidak ada di PHP, jadi digabung jadi satu constructor dengan nilai default.
- Interface PHP tidak boleh punya default method, jadi diganti trait.

## Penjelasan dan Hasil Output

### Pertemuan 01 - Class dan Object

Konversi class `iPhone` dari Java ke PHP. Dibuat dua object (iPhone 13 dan iPhone 14) lalu spesifikasinya ditampilkan lewat getter.

File:

- `iPhone.php` : class iPhone (property color & storage, constructor, getter)
- `main.php` : membuat object dan menampilkan hasilnya

Catatan konversi:

- Constructor di Java namanya sama dengan class, di PHP pakai `__construct()`.
- `this.color` jadi `$this->color`, dan pemanggilan method pakai `->`.
- Penggabungan string pakai `.` bukan `+`.
- `System.out.println()` diganti `echo` dan `PHP_EOL` untuk pindah baris.

Output

![Output](01/output.png)

Detail lengkap ada di [01/README.md](01/README.md).

### Pertemuan 02 - Constructor

Konversi class `Mahasiswa` yang punya tiga constructor (tanpa parameter, 2 parameter, 3 parameter) beserta getter dan setter.

File:

- `Mahasiswa.php` : class Mahasiswa
- `aplikasi.php` : program utama (padanan `Aplikasi.java`)

Catatan konversi:

- PHP tidak mendukung constructor overloading. Tiga constructor di Java digabung jadi satu `__construct()` dengan nilai default, jadi pemanggilan tanpa parameter, 2 parameter, dan 3 parameter tetap bisa dipakai.
- Bagian komentar di `Aplikasi.java` tidak ikut dibawa ke PHP.

Output

![Output](02/output.png)

Detail lengkap ada di [02/README.md](02/README.md).

### Pertemuan 03 - Inheritance

Ada dua program. `app.php` untuk bangun datar (BangunDatar dengan turunan Lingkaran, Persegi, Segitiga) dan `main.php` untuk Mahasiswa dengan turunan MahasiswaInternational.

File:

- `BangunDatar.php`, `Lingkaran.php`, `Persegi.php`, `Segitiga.php` : parent dan child class bangun datar
- `Mahasiswa.php`, `MahasiswaInternational.php` : parent dan child class mahasiswa
- `app.php` : program bangun datar (padanan `App.java`)
- `main.php` : program mahasiswa (padanan `Main.java`)

Catatan konversi:

- `extends` sama seperti di Java, dan memanggil constructor parent pakai `parent::__construct()` (di Java `super()`).
- Property `Mahasiswa` diubah dari private ke protected supaya bisa dipakai class turunan.
- Segitiga tidak punya `keliling()`, jadi yang dipanggil adalah milik BangunDatar (hasilnya hanya mencetak teks).
- Constructor MahasiswaInternational digabung jadi satu. Urutan parameter di versi 4 parameter diubah menjadi nama, nim, negara, umur (di Java: nama, nim, umur, negara) karena parameter yang punya default harus di belakang.
- Hasil luas dan keliling lingkaran dibulatkan 4 angka di belakang koma.

Output app.php (bangun datar)

![Output app.php (bangun datar)](03/output-app.png)

Output main.php (mahasiswa)

![Output main.php (mahasiswa)](03/output-main.png)

Detail lengkap ada di [03/README.md](03/README.md).

### Pertemuan 04 - Polymorphism

Class `Handphone` punya dua turunan, `Smartphone` dan `FeaturePhone`. Objek keduanya dimasukkan ke satu array lalu method yang sama (`nyalakan`, `telepon`, `matikan`) dipanggil lewat loop, hasilnya berbeda tergantung jenis objeknya.

File:

- `Handphone.php` : parent class
- `Smartphone.php` dan `FeaturePhone.php` : child class yang meng-override method parent
- `main.php` : program utama

Catatan konversi:

- Array Java `Handphone[]` diganti array PHP biasa, loop `for each` jadi `foreach`.
- `instanceof` di PHP dipakai dengan cara yang sama, dan casting tidak perlu karena PHP tidak ketat soal tipe.

Output

![Output](04/output.png)

Detail lengkap ada di [04/README.md](04/README.md).

### Pertemuan 05 - Asosiasi, Agregasi, dan Komposisi

Tiga jenis relasi antar class: asosiasi (Dokter merawat Pasien), agregasi (Tim punya Pemain) dan komposisi (Buku punya Bab).

File:

- `Dokter.php` dan `Pasien.php` : asosiasi
- `Tim.php` dan `Pemain.php` : agregasi
- `Buku.php` dan `Bab.php` : komposisi
- `main.php` : program utama

Catatan konversi:

- Asosiasi: Dokter hanya menerima objek Pasien lewat parameter method `merawat()`, tidak disimpan.
- Agregasi: objek Pemain dibuat di luar, lalu dimasukkan ke Tim lewat array. Pemain tetap ada walaupun Tim dihapus.
- Komposisi: objek Bab dibuat langsung di dalam class Buku, jadi hidupnya bergantung ke Buku.
- `List<Pemain>` dan `ArrayList` di Java diganti array PHP.

Output

![Output](05/output.png)

Detail lengkap ada di [05/README.md](05/README.md).

### Pertemuan 06 - Abstract Class dan Interface

Abstract class `Vehicle` dengan turunan `Car`, `Boat`, `Motor`, dan `Building`. Interface `Movable` dan `Fuelable` dipasang ke kendaraan yang memang bisa bergerak dan butuh bahan bakar, sedangkan `Building` tidak memakai keduanya.

File:

- `Vehicle.php` : abstract class
- `Movable.php` dan `Fuelable.php` : interface
- `Car.php`, `Boat.php`, `Motor.php`, `Building.php` : class turunan Vehicle
- `main.php` : program utama

Catatan konversi:

- Interface di PHP tidak boleh punya default method seperti di Java. Method default `refuel()` dipindah ke trait `FuelableDefault` (ada di `Fuelable.php`), dan dipakai oleh `Motor` dengan `use FuelableDefault;`.
- `Boat` dan `Car` meng-override `refuel()` sendiri.
- Pada `Car` teks refuel ditambah spasi supaya hasilnya enak dibaca.

Output

![Output](06/output.png)

Detail lengkap ada di [06/README.md](06/README.md).
