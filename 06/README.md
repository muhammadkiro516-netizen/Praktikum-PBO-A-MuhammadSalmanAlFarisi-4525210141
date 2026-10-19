# Pertemuan 06 - Abstract Class dan Interface

Nama : Muhammad Salman Al Farisi  
NIM  : 4525210141  
Kelas: PBO A

Abstract class `Vehicle` dengan turunan `Car`, `Boat`, `Motor`, dan `Building`. Interface `Movable` dan `Fuelable` dipasang ke kendaraan yang memang bisa bergerak dan butuh bahan bakar, sedangkan `Building` tidak memakai keduanya.

## File

- `Vehicle.php` : abstract class
- `Movable.php` dan `Fuelable.php` : interface
- `Car.php`, `Boat.php`, `Motor.php`, `Building.php` : class turunan Vehicle
- `main.php` : program utama

## Perbedaan dengan versi Java

- Interface di PHP tidak boleh punya default method seperti di Java. Method default `refuel()` dipindah ke trait `FuelableDefault` (ada di `Fuelable.php`), dan dipakai oleh `Motor` dengan `use FuelableDefault;`.
- `Boat` dan `Car` meng-override `refuel()` sendiri.
- Pada `Car` teks refuel ditambah spasi supaya hasilnya enak dibaca.

## Cara menjalankan

```
cd 06
php main.php
```

## Output

Output

```
Kendaraan: Mobil Sport
Mobil Sport bergerak di jalan.
Mobil Sport mengisi bahan bakar mobil

Kendaraan: Perahu Motor
Perahu Motor bergerak di air.
Perahu Motor mengisi bahan bakar solar khusus kapal.

Kendaraan: Motor Gravel
Motor Gravel bergerak di tanah gravel.
Mengisi bahan bakar umum.

Kendaraan: Gedung Tinggi
```

![Output](output.png)
