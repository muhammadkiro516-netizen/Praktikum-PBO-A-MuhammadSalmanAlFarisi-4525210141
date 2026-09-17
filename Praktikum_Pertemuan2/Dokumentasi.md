# Dokumentasi Tugas Pertemuan 2 - Rekening Bank

## 1. Tujuan Tugas
Tugas ini bertujuan untuk memahami konsep:
- class dan object
- constructor
- properti static
- validasi input
- method setter/getter
- named constructor / factory method
- konsep saldo, setor, tarik, dan biaya admin

## 2. Deskripsi Program
Program ini dibuat untuk mengelola rekening bank dengan fitur dasar:
- membuat rekening baru
- menghitung total rekening yang telah dibuat
- setor uang
- tarik uang
- memotong biaya administrasi
- menghitung bunga setahun

## 3. Struktur File
Folder project berisi:
- Main (1).java
- RekeningBank.java
- main (1).php
- RekeningBank.php

## 4. Aturan Bisnis yang Harus Dipenuhi
- saldo tidak boleh negatif
- nomor rekening tidak boleh kosong
- jumlah setor harus lebih dari 0
- jumlah tarik harus lebih dari 0
- penarikan tidak boleh melebihi saldo
- penarikan tidak boleh melebihi batas transaksi sekaligus
- biaya administrasi dipotong dari saldo, tetapi saldo tidak boleh di bawah 0
- bunga tahunan dihitung dengan rumus: pokok x 0.025

## 5. Konstanta yang Digunakan
- bunga tahunan = 0.025
- biaya administrasi = 5000
- batas penarikan sekali = 5000000

## 6. Hasil yang Diharapkan
- jumlah rekening di awal = 0
- setelah tiga objek dibuat = 3
- penarikan yang melebihi saldo ditolak
- penarikan yang melebihi batas transaksi ditolak
- saldo tetap tidak negatif setelah biaya admin dipotong
- bunga dihitung sesuai persentase

## 7. Langkah Verifikasi
### Java
```bash
javac "Main (1).java" "RekeningBank.java"
java Main