# Laporan Tugas Pertemuan 2 - Rekening Bank

## 1. Pendahuluan
Pada tugas pertemuan 2 ini, kami mempelajari konsep dasar pemrograman berorientasi objek (OOP) melalui implementasi kelas rekening bank. Fokus utama dari tugas ini adalah memahami cara membuat class, constructor, validasi data, serta penggunaan variabel dan method statis.

Program ini dibuat dalam dua bahasa, yaitu Java dan PHP, dengan tujuan agar mahasiswa dapat membandingkan prinsip OOP yang sama di dua lingkungan pemrograman yang berbeda.

## 2. Tujuan Tugas
Tujuan dari tugas ini adalah:
- memahami konsep class dan object
- memahami penggunaan constructor
- memahami penggunaan properti static
- menerapkan validasi input agar program aman dan konsisten
- memahami operasi dasar rekening seperti setor, tarik, dan biaya administrasi
- memahami cara menghitung bunga tahunan berdasarkan saldo

## 3. Struktur Project
Berikut struktur file yang terdapat dalam project:

- Main (1).java
- RekeningBank.java
- main (1).php
- RekeningBank.php

## 4. Deskripsi Program
Program ini dibuat untuk mengelola rekening bank dengan fitur utama sebagai berikut:
- membuat rekening baru
- menghitung jumlah rekening yang pernah dibuat
- menambahkan saldo melalui proses setor
- mengurangi saldo melalui proses tarik
- memotong biaya administrasi
- menghitung bunga setahun dari saldo atau pokok tertentu

## 5. Aturan Bisnis yang Diterapkan
Program ini menerapkan beberapa aturan agar rekening tetap valid dan aman, yaitu:
- saldo tidak boleh bernilai negatif
- nomor rekening tidak boleh kosong
- jumlah setoran harus lebih besar dari 0
- jumlah penarikan harus lebih besar dari 0
- penarikan tidak boleh melebihi saldo saat ini
- penarikan tidak boleh melebihi batas transaksi yang ditetapkan
- biaya administrasi dipotong dari saldo, tetapi saldo tidak boleh turun melebihi 0
- bunga tahunan dihitung dengan persentase 2,5% atau 0,025

## 6. Konstanta yang Digunakan
Konstanta yang digunakan dalam program adalah:
- bunga tahunan = 0.025
- biaya administrasi = 5000
- batas penarikan sekali = 5000000

Konstanta ini dibuat agar angka-angka penting tidak ditulis sembarangan di dalam method dan mudah diubah di satu tempat.

## 7. Implementasi dalam Java
Pada kelas Java, program menggunakan:
- `public static final` untuk konstanta
- `private static` untuk menghitung jumlah rekening
- constructor ringkas yang delegasi ke constructor lengkap
- validasi hanya dilakukan di constructor lengkap
- method `setor`, `tarik`, `potongBiayaAdmin`, `getJumlahRekening`, dan `bungaSetahun`

Tujuan penggunaan pendekatan ini adalah untuk menjaga konsistensi data dan memudahkan pemeliharaan program.

## 8. Implementasi dalam PHP
Pada versi PHP, program menggunakan konsep yang setara dengan Java, yaitu:
- `public const` untuk konstanta
- properti statis untuk menghitung jumlah rekening
- constructor default untuk validasi dan inisialisasi saldo
- named constructor `rekeningPelajar()` untuk membuat rekening pelajar dengan saldo awal nol
- method statis untuk memanggil data rekening dan menghitung bunga

## 9. Hasil yang Diharapkan
Ketika program dijalankan, output yang diharapkan adalah:
- jumlah rekening awal bernilai 0
- setelah tiga objek rekening dibuat, jumlah rekening menjadi 3
- transaksi yang melanggar aturan seperti tarik terlalu besar atau saldo tidak cukup akan ditolak
- biaya administrasi tidak membuat saldo menjadi negatif
- bunga dihitung sesuai rumus yang ditentukan

## 10. Langkah Verifikasi
Untuk memastikan program berjalan sesuai harapan, langkah verifikasi yang bisa dilakukan adalah:

### Java
```bash
javac "Main (1).java" "RekeningBank.java"
java Main