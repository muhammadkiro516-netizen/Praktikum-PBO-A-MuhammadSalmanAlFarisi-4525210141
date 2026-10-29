# Bukti Relasi: Komposisi dan Agregasi

Semua keluaran di bawah ini adalah hasil menjalankan kode di folder `java/` (kompilasi dengan `javac`, dijalankan dengan `java`).

## Percobaan 1: bisakah koleksi internal diubah dari luar?

`Buku.getBab()` adalah pintu keluar daftar bab. Bila daftar aslinya dikembalikan, siapa pun dapat mengosongkannya dari luar, sehingga objek `Buku` tidak lagi menjaga babnya sendiri.

Versi **salah**, `getBab()` mengembalikan `bab` apa adanya:

```text
$ javac -d out Eksperimen1.java model\*.java   (getBab() = return bab;  <- versi salah)
Jumlah bab sebelum : 2
clear() dari luar BERHASIL, jumlah bab sekarang: 0  <- MASALAH
```

Versi **benar**, `getBab()` mengembalikan `List.copyOf(bab)`:

```text
$ javac -d out Eksperimen1.java model\*.java   (getBab() = return List.copyOf(bab);  <- versi benar)
Jumlah bab sebelum : 2
clear() dari luar ditolak (UnsupportedOperationException)
Jumlah bab sesudah : 2
```

Kesimpulan: `getBab()` harus mengembalikan salinan yang tidak bisa diubah. Inilah syarat ketiga komposisi. Percobaan ini juga dijalankan oleh `Main.java` pada bagian "Mencoba mengubah koleksi internal dari luar".

## Percobaan 2: bisakah `Bab` dibuat dari luar `Buku`?

Syarat pertama dan kedua komposisi: bagian hanya lahir di dalam induknya.

```text
$ javac -d out Eksperimen2.java model\*.java
Eksperimen2.java:5: error: Bab(int,String) has private access in Bab
        Buku.Bab liar = new Buku.Bab(9, "Bab liar dari luar");
                        ^
1 error
```

Konstruktor `Bab` privat, sehingga kompilator menolak. Satu-satunya cara `Bab` lahir adalah `Buku.tambahBab(nomor, judul)`, yang menerima nomor dan judul, bukan objek `Bab`.

## Percobaan 3: bisakah `Peminjaman` dibuat dari luar `Anggota`?

```text
$ javac -d out Eksperimen3.java model\*.java
Eksperimen3.java:5: error: Peminjaman(Anggota,Koleksi,LocalDate) is not public in Peminjaman; cannot be accessed from outside package
        Peminjaman p = new Peminjaman(null, null, null);
                       ^
1 error
```

Konstruktor `Peminjaman` tidak publik. Hanya kelas sepaket (`Anggota`) yang dapat membuatnya, lewat `Anggota.catatPeminjaman(...)`. Itulah yang membuat `Anggota` dan `Peminjaman` berkomposisi.

## Percobaan 4: apa yang terjadi bila jurusan dibubarkan?

Sebagai pembanding, `Jurusan.Mahasiswa` berkonstruktor publik dan dibuat di luar:

```text
$ javac -d out Eksperimen4.java model\*.java
$ java -cp out Eksperimen4
Sebelum jurusan dibubarkan: TI - Teknik Informatika (1 mahasiswa)
Sesudah jurusan dibubarkan, Ani masih ada: 2024001 Ani Lestari
```

Mahasiswa tetap ada setelah `Jurusan` tidak ada lagi. Itulah agregasi. Sebaliknya, `Bab` tidak punya jalan untuk dijangkau tanpa `Buku`-nya.

## Catatan untuk PHP

1. **PHP tidak punya kelas bersarang.** Pada Java, `Buku.Bab` berkonstruktor privat sehingga hanya `Buku` yang bisa membuatnya. Di PHP, `Bab` adalah kelas biasa dengan konstruktor publik, jadi disiplin "hanya dibuat dari `Buku::tambahBab()`" dijaga lewat konvensi: anotasi `@internal`, dan tidak ada method `Buku` yang menerima objek `Bab` dari luar. Ini lebih lemah daripada penjagaan kompilator di Java.
2. **Array dikembalikan by-value.** `Buku::getBab()` memberi salinan larik, sehingga mengosongkan salinan itu tidak mengubah bab di dalam `Buku` (dibuktikan di `php/main.php`, bagian "Mencoba mengosongkan larik bab dari luar"). Namun **objek di dalam larik tetap berbagi referensi** dengan `Buku`. Karena itu `Bab` dibuat `readonly` (properti `private readonly`, tanpa setter), sehingga objek yang bocor pun tidak bisa diubah.
