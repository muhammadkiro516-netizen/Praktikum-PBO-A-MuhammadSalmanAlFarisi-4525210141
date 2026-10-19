# Analisis Kebutuhan Sistem Perpustakaan
**Pertemuan 7 — Pemodelan Relasi Objek dan UML**

Sumber: [starter/deskripsi-kebutuhan.md](starter/deskripsi-kebutuhan.md). Analisis ini menjadi dasar `uml/`, `java/`, dan `php/`, serta dilanjutkan sampai sesi 14.

---

## 1. Kata benda (calon kelas dan calon atribut)

| Kata benda dalam deskripsi | Keputusan | Alasan |
|---|---|---|
| koleksi | **Kelas** `Koleksi` (abstrak) | Punya data dan aturan sendiri, dan menjadi induk tiga jenis |
| buku, majalah, skripsi | **Kelas** `Buku`, `Majalah`, `Skripsi` | Aturan pinjamnya berbeda (14 hari, 3 hari, tidak boleh dipinjam), jadi tiap jenis menjawab sendiri |
| bab | **Kelas** `Bab` | Punya nomor dan judul, dan hidup hanya di dalam buku (komposisi) |
| anggota | **Kelas** `Anggota` | Punya identitas yang tidak berubah dan perilaku peminjam |
| jenis keanggotaan (mahasiswa, dosen, anggota kehormatan) | **Kontrak** `JenisKeanggotaan` dengan tiga implementasi | Denda per hari berbeda tiap jenis, dan deskripsi menyebut akan ada jenis baru, jadi bukan `enum` + `switch` |
| peminjaman | **Kelas** `Peminjaman` | Satu transaksi dengan tanggal dan aturan jatuh tempo sendiri |
| status (tersedia, dipinjam, rusak, hilang) | **Enum** `StatusKoleksi` | Himpunan nilai yang tetap dan kecil; "rusak" dan "hilang" membawa aturan (tidak dapat dipinjam) |
| perpustakaan | **Kelas** `PerpustakaanService` | Pihak yang menyimpan koleksi dan anggota serta mengoordinasi `pinjam()` dan `kembalikan()` |
| petugas | **Aktor**, bukan kelas (untuk sesi ini) | Belum ada data atau aturan yang melekat pada petugas. Bila kelak sistem harus mencatat siapa yang memproses transaksi, ia menjadi kelas |

Enam kelas atau lebih sudah terpenuhi: `Koleksi`, `Buku`, `Majalah`, `Skripsi`, `Bab`, `Anggota`, `Peminjaman`, `PerpustakaanService`, ditambah `JenisKeanggotaan` beserta tiga implementasinya dan enum `StatusKoleksi`.

### Kata benda yang dicoret karena hanya atribut atau bukan kelas

| Kata benda | Menjadi | Alasan pencoretan |
|---|---|---|
| nomor induk, judul, penulis, tahun terbit, jumlah eksemplar | atribut `Koleksi` | Hanya nilai milik koleksi, tidak punya perilaku sendiri |
| nomor, judul (bab) | atribut `Bab` | Sama |
| nomor anggota, nama | atribut `Anggota` | Nilai identitas; nomor anggota `final` karena tidak pernah berubah |
| tanggal pinjam, tanggal jatuh tempo, tanggal kembali | atribut `Peminjaman` | Nilai, bukan benda. Tanggal jatuh tempo diturunkan dari tanggal pinjam |
| masa pinjam | method `masaPinjamHari()` | Bukan data yang disimpan, tetapi jawaban tiap jenis koleksi |
| denda, denda per hari | method `hitungDenda()` dan `dendaPerHari()` | Denda adalah **hasil hitung**, bukan benda yang disimpan. Besarnya milik jenis keanggotaan |
| jumlah eksemplar tersedia | atribut `jumlahEksemplar` | Berubah mengikuti pinjam dan kembali |
| riwayat peminjaman, daftar koleksi yang sedang dipinjam | method `riwayat()` dan `daftarSedangDipinjam()` | Hasil penyaringan dari data `Peminjaman`, bukan kelas baru |
| mahasiswa, dosen, anggota kehormatan | bukan subkelas `Anggota`, melainkan implementasi `JenisKeanggotaan` | Orang yang sama bisa berganti jenis; yang berbeda hanya aturan dendanya |
| Universitas Pancasila, sistem | konteks | Bukan objek dalam model |

---

## 2. Kata kerja (calon method)

| Kata kerja dalam deskripsi | Method | Pemilik | Alasan pemilik |
|---|---|---|---|
| menyimpan koleksi / menambah koleksi | `tambahKoleksi(k)` | `PerpustakaanService` | Layanan memegang katalog |
| mendaftarkan anggota | `daftarkanAnggota(a)` | `PerpustakaanService` | Layanan memegang daftar anggota |
| mencari koleksi | `cariKoleksi()`, `cariJudul()` | `PerpustakaanService` | Pencarian butuh seluruh katalog |
| meminjam / mencatat peminjaman | `pinjam(nomorAnggota, nomorInduk)` | `PerpustakaanService` | Melibatkan anggota, koleksi, dan daftar peminjaman sekaligus |
| mengembalikan / mencatat pengembalian | `kembalikan(...)` | `PerpustakaanService` | Sama |
| dapat dipinjam | `dapatDipinjam()`, `bolehDipinjam()` | `Koleksi` dan turunannya | Hanya koleksi yang tahu jenis, status, dan stoknya |
| mengurangi / menambah eksemplar | `kurangiStok()`, `tambahStok()` | `Koleksi` | Stok adalah data milik koleksi |
| menghitung tanggal jatuh tempo | constructor `Peminjaman` | `Peminjaman` | Rumusnya: tanggal pinjam + `koleksi.masaPinjamHari()` |
| dikenai denda / menghitung denda | `hitungDenda(acuan)` | `Peminjaman` | Butuh tanggal jatuh tempo dan tanggal kembali, lalu meminta `dendaPerHari()` ke jenis keanggotaan |
| terdiri atas bab | `tambahBab(nomor, judul)` | `Buku` | Satu-satunya jalan Bab lahir |
| melihat daftar koleksi yang sedang dipinjam | `daftarSedangDipinjam()` | `PerpustakaanService` | Petugas |
| melihat riwayat peminjaman sendiri | `riwayat(nomorAnggota)` | `PerpustakaanService` | Anggota |

---

## 3. Aturan bisnis (invariant)

Kalimat yang memuat "tidak boleh", "harus", "hanya", atau angka batas, beserta tempatnya di kode.

| No | Aturan | Tempat di kode |
|---|---|---|
| R1 | Buku dipinjam 14 hari, majalah 3 hari, skripsi tidak dapat dipinjam | `masaPinjamHari()` dan `bolehDipinjam()` di `Buku`, `Majalah`, `Skripsi` |
| R2 | Bab tidak punya arti di luar buku dan tidak pernah berpindah buku | `Buku.Bab` bersarang, constructor privat, tidak ada method yang menerima objek `Bab` |
| R3 | Nomor anggota tidak pernah berubah | `Anggota.nomorAnggota` `final`, tanpa setter |
| R4 | Seorang anggota tidak boleh meminjam lebih dari tiga koleksi pada saat bersamaan | `PerpustakaanService.pinjam()` memeriksa `BATAS_PINJAMAN_AKTIF` |
| R5 | Tanggal jatuh tempo = tanggal pinjam + masa pinjam koleksi | Constructor `Peminjaman` |
| R6 | Tanggal kembali kosong selama belum dikembalikan | `Peminjaman.tanggalKembali` bernilai `null`, dibuka sebagai `Optional` |
| R7 | Koleksi hanya dapat dipinjam bila ada eksemplar tersedia; pinjam mengurangi satu, kembali menambah satu | `Koleksi.dapatDipinjam()`, `kurangiStok()`, `tambahStok()` |
| R8 | Denda per hari: mahasiswa Rp1.000, dosen Rp500, anggota kehormatan bebas | `dendaPerHari()` di `Mahasiswa`, `Dosen`, `AnggotaKehormatan` |
| R9 | Jenis keanggotaan baru akan muncul | `JenisKeanggotaan` berupa interface: jenis baru cukup menambah implementasi |
| R10 | Koleksi rusak atau hilang tidak dapat dipinjam apa pun jenisnya | `StatusKoleksi.membolehkanPeminjaman()` dipakai `Koleksi.dapatDipinjam()` |
| R11 | Buku terdiri atas "beberapa" bab, jadi minimal satu (multiplicity `1..*`) | Constructor `Buku` menolak daftar judul bab kosong |
| R12 | Jumlah eksemplar tidak boleh negatif; stok habis tidak boleh dikurangi lagi | Constructor dan `kurangiStok()` di `Koleksi` |

---

## 4. Dua petunjuk rancangan dalam deskripsi

1. **"Bab tidak punya arti apa pun di luar buku yang memuatnya, dan tidak pernah berpindah dari satu buku ke buku lain."** Inilah uji komposisi: bagian tidak bisa hidup tanpa induknya dan hanya punya satu induk. Wujud di kode: `Bab` dibuat di dalam `Buku`, tidak ada jalan menyerahkan objek `Bab` dari luar, dan getter mengembalikan salinan tak-berubah.
2. **"Pihak perpustakaan memperkirakan akan ada jenis keanggotaan baru."** Ini sinyal Open-Closed (pertemuan 5): bila jenis keanggotaan dibuat `enum` dan denda dihitung dengan `switch`, setiap jenis baru memaksa menyunting `switch` itu. Karena itu `JenisKeanggotaan` dibuat kontrak, dan `Main` membuktikannya dengan jenis `Alumni` (Rp750/hari) yang ditambahkan tanpa menyentuh kelas lama.

---

## 5. Use case diagram: pilihan include dan extend

Diagram: [uml/usecase.puml](uml/usecase.puml). Dua aktor (Anggota, Petugas) dan sembilan use case.

| Relasi | Pilihan | Alasan |
|---|---|---|
| **Catat peminjaman** `<<include>>` **Periksa aturan peminjaman** | include | Pemeriksaan (status, stok, batas tiga koleksi) **selalu** terjadi pada setiap peminjaman. Bila dihapus, peminjaman tidak lengkap |
| **Hitung denda keterlambatan** `<<extend>>` **Catat pengembalian** | extend | Denda **hanya kadang-kadang** muncul, yaitu bila terlambat dan bila jenis keanggotaannya tidak bebas denda. Pengembalian tetap utuh tanpanya |

Uji cepat yang dipakai: bila use case dasar **tidak lengkap** tanpa bagian itu, pakai include. Bila bagian itu **opsional dan bersyarat**, pakai extend.

---

## 6. Keputusan relasi di class diagram

Diagram: [uml/class.puml](uml/class.puml) (hasil render [class.png](uml/class.png)). Uji yang dipakai untuk setiap relasi: **"Kalau induknya dihapus, apakah bagian ini masih masuk akal?"**

| Relasi | Jenis | Multiplicity | Hasil uji | Wujud di kode |
|---|---|---|---|---|
| `Buku` — `Bab` | **Komposisi** | 1 ke `1..*` | Bab tanpa buku tidak punya arti | Bab dibuat di dalam `Buku`; constructor `Bab` privat; `getBab()` = `List.copyOf`; constructor `Buku` menolak daftar kosong |
| `PerpustakaanService` — `Peminjaman` | **Komposisi** | 1 ke `*` | Catatan peminjaman tidak ada tanpa layanan yang mencatatnya | `Peminjaman` lahir di dalam `pinjam()`; `getPeminjaman()` = `List.copyOf` |
| `PerpustakaanService` — `Koleksi` | **Agregasi** | 1 ke `*` | Koleksi tetap ada walau katalog dibubarkan | Diterima dari luar lewat `tambahKoleksi(k)`; `hapusKoleksi()` mengembalikan objeknya |
| `PerpustakaanService` — `Anggota` | **Agregasi** | 1 ke `*` | Anggota tetap orang yang sama walau layanan diganti | Diterima dari luar lewat `daftarkanAnggota(a)` |
| `Peminjaman` — `Anggota` | **Asosiasi** | `*` ke 1 | Anggota hidup tanpa peminjaman; peminjaman hanya merujuk | Field `anggota` diisi lewat constructor |
| `Peminjaman` — `Koleksi` | **Asosiasi** | `*` ke 1 | Koleksi hidup tanpa peminjaman | Field `koleksi` diisi lewat constructor |
| `Anggota` — `JenisKeanggotaan` | **Asosiasi** | `*` ke 1 | Jenis adalah kontrak yang dipakai bersama | Field `jenis` |
| `Koleksi` — `StatusKoleksi` | **Asosiasi** | `*` ke 1 | Enum adalah nilai, bukan bagian | Field `status` |
| `Peminjaman` ⇢ `JenisKeanggotaan` | **Dependensi** | tanpa | Hanya dipakai sesaat saat `hitungDenda()` | Pemanggilan `anggota.getJenis().dendaPerHari()`, tidak disimpan di field |
| `Koleksi` ◁ `Buku`, `Majalah`, `Skripsi` | Generalisasi | tanpa | "adalah sebuah koleksi" | `extends Koleksi` |
| `JenisKeanggotaan` ◁ `Mahasiswa`, `Dosen`, `AnggotaKehormatan` | Realisasi | tanpa | Memenuhi kontrak | `implements JenisKeanggotaan` |

Keempat jenis yang diminta modul (komposisi, agregasi, asosiasi, dependensi) semuanya ada.

### Jawaban TODO 1 di `PerpustakaanService`: agregasi atau komposisi untuk koleksi dan anggota?

**Agregasi.** Alasannya:
- Petugas membuat objek `Buku` atau `Anggota` di luar, lalu menyerahkannya ke layanan. Dalam kode itu berarti objek bagian **datang dari luar**.
- Buku fisik ada sebelum sistem dan tetap ada sesudahnya. Layanan hanya mengatalogkan.
- Bila dibuat komposisi, layanan harus membuat sendiri setiap koleksi dan getter tidak boleh membocorkan objeknya. Itu tidak cocok dengan kenyataan bahwa koleksi dibeli, disumbangkan, atau dipindahkan antarperpustakaan. Perbandingan nyata ada di [percobaan-mandiri.md](percobaan-mandiri.md), latihan E1.

Sebaliknya `Peminjaman` dibuat **komposisi** karena ia adalah catatan yang lahir dari proses `pinjam()` dan tidak punya arti tanpa layanan pencatatnya.

### Sequence diagram

[uml/sequence-pinjam.puml](uml/sequence-pinjam.puml): dua aktor (Anggota, Petugas) dan empat objek (`PerpustakaanService`, `a : Anggota`, `k : Koleksi`, serta `p : Peminjaman` yang dibuat di tengah alur), dengan dua alur alternatif bersarang: koleksi tidak dapat dipinjam, dan anggota sudah meminjam tiga koleksi.
