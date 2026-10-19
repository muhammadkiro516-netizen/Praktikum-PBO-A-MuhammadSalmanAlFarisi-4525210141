# Pertemuan 01 - Class dan Object

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Konversi class `iPhone` dari Java ke PHP. Dibuat dua object (iPhone 13 dan iPhone 14) lalu spesifikasinya ditampilkan lewat getter.

## File

- `iPhone.php` : class iPhone (property color & storage, constructor, getter)
- `main.php` : membuat object dan menampilkan hasilnya

## Perbedaan dengan versi Java

- Constructor di Java namanya sama dengan class, di PHP pakai `__construct()`.
- `this.color` jadi `$this->color`, dan pemanggilan method pakai `->`.
- Penggabungan string pakai `.` bukan `+`.
- `System.out.println()` diganti `echo` dan `PHP_EOL` untuk pindah baris.

## Cara menjalankan

```
cd 01
php main.php
```

## Output

Output

```
Spesifikasi iPhone 13
Warna: Red
Storage: 128GB
Spesifikasi iPhone 14
Warna: Grey
Storage: 256GB
```

![Output](output.png)
