# Analisis Kebutuhan: Sistem Perpustakaan (Sesi 9)

Analisis ini dibuat dari [deskripsi-kebutuhan.md](deskripsi-kebutuhan.md) dan menjadi dasar class diagram di [uml/perpustakaan.puml](uml/perpustakaan.puml) serta kode di folder `java/`.

## 1. Kata benda: kelas atau atribut?

| Kata benda | Keputusan | Alasan |
|---|---|---|
| koleksi | **Kelas** `Koleksi` (abstract) | Punya data dan aturan sendiri, dan menjadi induk dari tiga jenis |
| buku, majalah, skripsi | **Kelas** turunan `Buku`, `Majalah`, `Skripsi` | Beda perilaku: masa pinjam 14 hari, 3 hari, dan tidak boleh dipinjam |
| bab | **Kelas** `Bab` (bersarang di `Buku`) | Punya nomor dan judul, tetapi hidupnya bergantung pada buku |
| anggota | **Kelas** `Anggota` | Punya identitas dan aturan batas pinjam |
| jenis keanggotaan | **Interface** `JenisKeanggotaan` + tiga kelas | Denda per hari berbeda, dan akan ada jenis baru |
| mahasiswa, dosen, anggota kehormatan | **Kelas** `KeanggotaanMahasiswa`, `KeanggotaanDosen`, `KeanggotaanKehormatan` | Tiga implementasi dari jenis keanggotaan |
| peminjaman | **Kelas** `Peminjaman` | Mencatat satu transaksi lengkap dengan tanggalnya |
| status (tersedia, dipinjam, rusak, hilang) | **Enum** `StatusKoleksi` | Himpunan nilainya tetap |
| perpustakaan / petugas | **Kelas** `PerpustakaanService` | Pekerjaan petugas (menambah, mencatat, melihat) dikumpulkan di satu layanan |

## 2. Kata benda yang dicoret (hanya atribut)

| Kata benda | Menjadi | Alasan dicoret |
|---|---|---|
| nomor induk, judul, penulis, tahun terbit, jumlah eksemplar | atribut `Koleksi` | Hanya nilai milik koleksi, tidak punya perilaku sendiri |
| nomor bab, judul bab | atribut `Bab` | Hanya nilai milik bab |
| nomor anggota, nama | atribut `Anggota` | Hanya nilai milik anggota |
| tanggal pinjam, tanggal jatuh tempo, tanggal kembali | atribut `Peminjaman` | Hanya nilai milik peminjaman |
| masa pinjam | method `masaPinjamHari()` | Nilainya ditentukan jenis koleksi, bukan disimpan per objek |
| denda | method `hitungDenda()` | Dapat dihitung dari hari terlambat dan jenis keanggotaan, sehingga tidak perlu disimpan |
| eksemplar tersedia | dari `jumlahEksemplar` | Jumlah eksemplar sudah berkurang setiap ada peminjaman |
| daftar koleksi yang sedang dipinjam, riwayat | hasil method | Dihitung dari data yang ada (`daftarSedangDipinjam()`, `getRiwayat()`) |

## 3. Kata kerja menjadi method

| Kata kerja | Method | Pemilik |
|---|---|---|
| menambah koleksi | `tambahKoleksi(k)` | `PerpustakaanService` |
| mendaftarkan anggota | `daftarkanAnggota(a)` | `PerpustakaanService` |
| mencatat peminjaman | `pinjam(...)`, `Anggota.catatPeminjaman(...)` | `PerpustakaanService`, `Anggota` |
| mencatat pengembalian | `kembalikan(...)`, `Peminjaman.kembalikan(...)` | `PerpustakaanService`, `Peminjaman` |
| melihat daftar yang sedang dipinjam | `daftarSedangDipinjam()` | `PerpustakaanService` |
| mencari koleksi | `cariKoleksi(nomorInduk)` | `PerpustakaanService` |
| melihat riwayat | `riwayatAnggota(nomorAnggota)` | `PerpustakaanService` |
| mengurangi atau menambah eksemplar | `kurangiStok()`, `tambahStok()` | `Koleksi` |
| menghitung jatuh tempo, denda | konstruktor `Peminjaman`, `hitungDenda()` | `Peminjaman` |

## 4. Kalimat aturan ("tidak boleh", "harus", "hanya") dan penjaganya

| Aturan | Dijaga di |
|---|---|
| Skripsi tidak dapat dipinjam sama sekali | `Skripsi.bolehDipinjam()` bernilai `false`, diperiksa di `PerpustakaanService.pinjam` |
| Nomor anggota tidak pernah berubah | `Anggota.nomorAnggota` bersifat `final`, tanpa setter |
| Anggota tidak boleh meminjam lebih dari tiga koleksi bersamaan | `Anggota.catatPeminjaman` dan `bolehMeminjamLagi()` |
| Koleksi hanya dapat dipinjam bila ada eksemplar tersedia | `Koleksi.tersedia()` dan `kurangiStok()` |
| Koleksi rusak atau hilang tidak dapat dipinjam apa pun jenisnya | `StatusKoleksi.bisaDipinjam()` dipakai oleh `tersedia()` |
| Jatuh tempo = tanggal pinjam + masa pinjam koleksi | Konstruktor `Peminjaman` |
| Tanggal kembali kosong selama belum dikembalikan | `Peminjaman.tanggalKembali` bernilai `null`, `sudahKembali()` |
| Jumlah eksemplar tidak boleh negatif | Konstruktor `Koleksi` |
| Denda: mahasiswa Rp1.000, dosen Rp500, kehormatan bebas | `JenisKeanggotaan.dendaPerHari()` pada tiap implementasi |
| Bab hanya berarti di dalam bukunya dan tidak berpindah buku | Konstruktor `Bab` privat, dibuat hanya oleh `Buku.tambahBab` |

## 5. Dua petunjuk rancangan

1. **"Bab tidak punya arti apa pun di luar buku yang memuatnya."** Bab tidak bisa hidup tanpa bukunya dan tidak pernah berpindah, jadi relasinya **komposisi**. Di kode ini diwujudkan dengan kelas bersarang `Buku.Bab` berkonstruktor privat, method `tambahBab(nomor, judul)` yang menerima nomor dan judul (bukan objek `Bab`), dan `getBab()` yang mengembalikan salinan tak-berubah.
2. **"Akan ada jenis keanggotaan baru."** Bila jenis keanggotaan dibuat sebagai enum, setiap jenis baru memaksa kita membuka dan menyunting enum itu. Dengan interface `JenisKeanggotaan`, jenis baru cukup berupa satu kelas baru yang mengimplementasikannya, tanpa menyentuh `Anggota`, `Peminjaman`, maupun layanan (Open-Closed). Sebaliknya `StatusKoleksi` tetap enum karena empat statusnya tetap.

## 6. Keputusan relasi

| Relasi | Jenis | Multiplicity | Alasan |
|---|---|---|---|
| `Buku` dan `Bab` | Komposisi | 1 ke 0..* | Bab tidak bisa hidup tanpa buku. Buku baru dibuat dahulu, lalu babnya ditambahkan, sehingga multiplicity `Bab` adalah `0..*` |
| `Anggota` dan `Peminjaman` | Komposisi | 1 ke 0..* | Peminjaman dibuat di dalam `Anggota` (konstruktornya tidak publik) dan tidak bermakna tanpa anggotanya |
| `Peminjaman` dan `Koleksi` | Asosiasi | * ke 1 | Koleksi tetap ada walau tidak ada yang meminjamnya |
| `Anggota` dan `JenisKeanggotaan` | Asosiasi | 1 ke 1 | Anggota hanya memegang rujukan ke satu jenis |
| `Koleksi` dan `StatusKoleksi` | Asosiasi | 1 ke 1 | Status adalah nilai milik koleksi |
| `Jurusan` dan `Mahasiswa` | Agregasi | 1 ke 0..* | Mahasiswa dibuat di luar, diterima lewat `daftarkan`, dan tetap hidup bila jurusan dibubarkan |
| `PerpustakaanService` dan `Koleksi`, `Anggota` | **Agregasi** | 1 ke 0..* | Lihat alasan di bawah |

**Mengapa `PerpustakaanService` mengagregasi, bukan mengkomposisi?** Objek `Koleksi` dan `Anggota` dibuat di luar layanan lalu diterima lewat `tambahKoleksi(k)` dan `daftarkanAnggota(a)`. Bila layanan diganti atau dibubarkan, buku dan anggotanya tetap bermakna dan bisa dipindahkan ke layanan lain. Pada komposisi, bagian lahir dan mati bersama keseluruhannya, dan itu tidak berlaku di sini.

## 7. Perbaikan pada kerangka diagram

Kerangka menulis `Koleksi <|-- Bab` (TODO 5). Itu salah: pewarisan berarti "adalah", padahal bab *bukan* sebuah koleksi, melainkan *bagian* dari buku. Hubungan yang benar adalah komposisi `Buku *-- Bab`.

## 8. Cakupan kode

Class diagram dan kode Java mencakup seluruh model dan layanan. Folder `php/` mengerjakan bagian relasi yang diminta kerangka (`Buku`, `Bab`, `Jurusan`, `Mahasiswa`), karena starter PHP memang hanya memuat bagian itu.
