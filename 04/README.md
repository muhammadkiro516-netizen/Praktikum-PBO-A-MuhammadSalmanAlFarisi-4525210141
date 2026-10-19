# Pertemuan 04 - Polymorphism

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Class `Handphone` punya dua turunan, `Smartphone` dan `FeaturePhone`. Objek keduanya dimasukkan ke satu array lalu method yang sama (`nyalakan`, `telepon`, `matikan`) dipanggil lewat loop, hasilnya berbeda tergantung jenis objeknya.

## File

- `Handphone.php` : parent class
- `Smartphone.php` dan `FeaturePhone.php` : child class yang meng-override method parent
- `main.php` : program utama

## Perbedaan dengan versi Java

- Array Java `Handphone[]` diganti array PHP biasa, loop `for each` jadi `foreach`.
- `instanceof` di PHP dipakai dengan cara yang sama, dan casting tidak perlu karena PHP tidak ketat soal tipe.

## Cara menjalankan

```
cd 04
php main.php
```

## Output

Output

```
Smartphone Samsung Galaxy S21 sedang booting.
Melakukan panggilan video ke nomor 08123456789
Smartphone Samsung Galaxy S21 sedang shutdown.

Feature Phone Nokia 3310 dinyalakan.
Melakukan panggilan suara ke nomor 08123456789
Feature Phone Nokia 3310 dimatikan.

Mengakses internet melalui Smartphone.
Memainkan game Snake.
```

![Output](output.png)
