# Pilihan Koleksi dan Catatan Percobaan (Sesi 11)

## 1. Struktur data yang dipilih dan alasannya

| Data | Java | PHP | Alasan |
|---|---|---|---|
| Buku, dicari berdasarkan ISBN (nomor induk) | `Repository<Buku>` yang memakai `LinkedHashMap<String, Buku>` | `KoleksiBuku` yang memakai array asosiatif | Pencarian paling sering lewat ISBN. Pada `List` setiap pencarian harus memeriksa item satu per satu, sedangkan `Map` langsung menuju kuncinya lewat `get(isbn)`, sehingga tetap cepat walau bukunya ribuan. `LinkedHashMap` dipilih (bukan `HashMap`) agar urutan penyimpanan terjaga dan keluaran laporan stabil. |
| Jumlah pinjaman per anggota | `Map<String, Integer>` | array asosiatif `idAnggota => jumlah` | ID anggota unik dan yang dicari selalu ID-nya. `Set` tidak cukup karena yang disimpan adalah hitungannya, bukan sekadar keanggotaan. |
| Hasil `cariSemua()` | `List<Buku>` tak-berubah (`List.copyOf`) | array hasil `array_values` | Urutan penting dan boleh dikembalikan sebagai daftar. Salinan tak-berubah membuat koleksi internal tidak bocor. |

`List` berguna untuk hasil yang berurutan, `Set` untuk data tanpa duplikat (misalnya daftar NIM), dan `Map` untuk pasangan kunci dan nilai. Di `LibraryService`, stok dan judul kini menjadi milik objek `Buku`, sehingga beberapa `Map` paralel yang dahulu terpisah (stok, judul, pinjaman) cukup digantikan satu `Repository<Buku>` dan satu `Map` pinjaman.

**Generics menolak tipe yang salah saat kompilasi.** Menyimpan `Anggota` ke `Repository<Buku>`:

```text
$ javac -d out Coba.java    (menyimpan Anggota ke Repository<Buku>)
g/Coba.java:7: error: incompatible types: Anggota cannot be converted to Buku
        repo.simpan(new Anggota("A-001", "Ani", new KeanggotaanMahasiswa()));
                    ^
Note: Some messages have been simplified; recompile with -Xdiags:verbose to get full output
1 error
```

Pada PHP tidak ada generics. `KoleksiBuku::simpan(Buku $b)` menolak tipe lain dengan `TypeError`, tetapi baru saat program **berjalan**. Itulah perbedaan utama dengan Java.

## 2. Pengganti `null` (Optional dan nullable)

| Tempat | Sebelum | Sesudah |
|---|---|---|
| `PerpustakaanService.cariKoleksi` (Java) | mengembalikan `null` bila nomor induk tidak ada, sehingga `cariKoleksi("X").getJudul()` meledak sebagai `NullPointerException` | `Optional<Koleksi>`: pemanggil dipaksa menangani kasus kosong, misalnya `.map(Koleksi::getJudul).orElse("(tidak ada)")` |
| `Repository.cariById` (Java) | tidak ada | `Optional<T>` sejak awal |
| `LibraryService.cariBuku` (Java dan PHP) | tidak ada | Java: `Optional<Buku>`. PHP: `?Buku` dipakai dengan `?->getJudul() ?? '(tidak ada)'` |

## 3. Uji yang gagal dengan sengaja

Pada `LibraryService.pinjam`, satu operator diubah dari `>=` menjadi `>` pada pemeriksaan batas pinjam, lalu seluruh uji dijalankan ulang:

```text
$ (penjalan uji pengganti)   satu baris diubah: >= menjadi >
Running id.ac.univpancasila.perpustakaan.RepositoryTest
Running id.ac.univpancasila.perpustakaan.service.LibraryServiceTest
  [GAGAL] Skenario 5: pinjaman ke-4 melempar BatasPinjamTerlampauiException -> org.opentest4j.AssertionFailedError: Expected id.ac.univpancasila.perpustakaan.exception.BatasPinjamTerlampauiException to be thrown, but nothing was thrown.
Tests run: 16, Failures: 1, Errors: 0
```

Dari 16 uji, hanya **satu** yang gagal, yaitu uji skenario 5 (pinjaman ke-4). Penyebabnya, dengan `>` anggota baru ditolak saat sudah memegang **empat** buku, sehingga pinjaman ke-4 lolos dan `BatasPinjamTerlampauiException` tidak dilempar. Uji lainnya tetap lulus karena tidak menyentuh batas itu. Pesan gagalnya langsung menunjuk jenis exception yang diharapkan dan fakta bahwa tidak ada yang dilempar, sehingga bagian yang rusak mudah ditemukan tanpa menjalankan program secara manual. Itulah gunanya unit test: kerusakan kecil terdeteksi seketika.

> Keluaran di atas dijalankan dengan penjalan uji pengganti di lingkungan penyusunan (JUnit tidak dapat diunduh di sana). Format pesan `AssertionFailedError` sama dengan JUnit 5. Ganti dengan keluaran `mvn test` milikmu setelah mengulang percobaan ini, lalu hapus catatan ini.

## 4. Latihan mandiri

**Latihan 1: `TreeMap` menggantikan `LinkedHashMap` pada laporan 3.** Urutan hasil berubah dari urutan kemunculan menjadi urutan abjad kunci:

```text
$ java LaporanApp   (LinkedHashMap diganti TreeMap)
=== Laporan 3: jumlah buku per penulis ===
  A. Silberschatz: 1
  A. Tanenbaum: 1
  A. W. Pribadi: 2
  R. Elmasri: 1
```

`LinkedHashMap` menjaga urutan *dimasukkan*, `TreeMap` mengurutkan menurut kunci, dan `HashMap` tidak menjamin urutan apa pun.

**Latihan 2: bounded type `<N extends Number>`.** Pemanggilan dengan `List<String>` ditolak saat kompilasi:

```text
$ javac Batas.java
b/Batas.java:11: error: method jumlahkan in class Batas cannot be applied to given types;
        System.out.println(jumlahkan(List.of("a", "b")));
                           ^
  required: List<N>
  found:    List<String>
  reason: inference variable E has incompatible bounds
    upper bounds: Number,Object
    lower bounds: String
  where N,E are type-variables:
    N extends Number declared in method <N>jumlahkan(List<N>)
    E extends Object declared in method <E>of(E,E)
1 error
```

Setelah baris itu dihapus, program berjalan:

```text
$ java Batas   (tanpa baris List<String>)
227.5
```

**Latihan 3 (PHP): `array_filter` tanpa `array_values`, lalu `json_encode`.** `array_filter` mempertahankan kunci asli, sehingga hasil dengan kunci yang tidak berurutan (misalnya `[0 => 80, 2 => 92]`) berubah menjadi **objek** JSON `{"0":80,"2":92}`, bukan larik `[80,92]`. Karena itu `array_values` dipakai setelah `array_filter` (seperti pada `KoleksiBuku::filter`).

> **BELUM DIVERIFIKASI** (PHP tidak dapat dijalankan saat berkas ini disusun). Jalankan `php -r "echo json_encode(array_filter([80,55,92,70], fn($n)=>$n>=70));"` lalu bandingkan dengan keluaran yang disebut di atas, kemudian hapus catatan ini.
