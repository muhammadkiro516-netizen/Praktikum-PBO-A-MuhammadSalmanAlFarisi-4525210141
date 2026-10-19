# Pertemuan 03 - Inheritance

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Ada dua program. `app.php` untuk bangun datar (BangunDatar dengan turunan Lingkaran, Persegi, Segitiga) dan `main.php` untuk Mahasiswa dengan turunan MahasiswaInternational.

## File

- `BangunDatar.php`, `Lingkaran.php`, `Persegi.php`, `Segitiga.php` : parent dan child class bangun datar
- `Mahasiswa.php`, `MahasiswaInternational.php` : parent dan child class mahasiswa
- `app.php` : program bangun datar (padanan `App.java`)
- `main.php` : program mahasiswa (padanan `Main.java`)

## Perbedaan dengan versi Java

- `extends` sama seperti di Java, dan memanggil constructor parent pakai `parent::__construct()` (di Java `super()`).
- Property `Mahasiswa` diubah dari private ke protected supaya bisa dipakai class turunan.
- Segitiga tidak punya `keliling()`, jadi yang dipanggil adalah milik BangunDatar (hasilnya hanya mencetak teks).
- Constructor MahasiswaInternational digabung jadi satu. Urutan parameter di versi 4 parameter diubah menjadi nama, nim, negara, umur (di Java: nama, nim, umur, negara) karena parameter yang punya default harus di belakang.
- Hasil luas dan keliling lingkaran dibulatkan 4 angka di belakang koma.

## Cara menjalankan

```
cd 03
php app.php
php main.php
```

## Output

Output app.php (bangun datar)

```
Menghitung luas bangun datar
Menghitung keliling bangun datar
Luas lingkaran: 706.8583
Keliling lingkaran: 94.2478
Luas Bujur Sangkar: 100
Keliling Bujur Sangkar: 40
Luas Segitiga: 40
Menghitung keliling bangun datar
```

![Output app.php (bangun datar)](output-app.png)

Output main.php (mahasiswa)

```
Nama: Paolo Dicanio
NIM: INT12345
Umur: 21
Negara Asal: Italy

Nama: Sarah
NIM: INT67890
Umur: 22
Negara Asal: Australia

Nama: David
NIM: INT54321
Umur: 23
Negara Asal: UK
```

![Output main.php (mahasiswa)](output-main.png)
