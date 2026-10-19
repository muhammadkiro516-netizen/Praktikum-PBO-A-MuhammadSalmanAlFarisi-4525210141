# Persiapan Demo Pertemuan 7
Jawaban untuk tujuh pertanyaan demo di modul (bagian I). Semua jawaban merujuk ke berkas di folder ini.
Pelajari alurnya, lalu jalankan sendiri `java Main` dan `php main.php` sebelum demo. Modul menegaskan bahwa kode yang tidak dapat dijelaskan tidak dinilai.

## 1. Tunjuk satu komposisi di diagram, lalu baris kode yang mewujudkannya. Apa yang membuatnya komposisi dan bukan agregasi?
Di `uml/class.puml`: `Buku "1" *-- "1..*" Bab`. Di kode (`java/model/Buku.java`):

```java
public void tambahBab(int nomor, String judulBab) { buatBab(nomor, judulBab); }   // parameter nomor dan judul, BUKAN objek Bab
...
bab.add(new Bab(nomor, judulBab));            // Bab dibuat DI DALAM Buku
private Bab(int nomor, String judul) { ... }  // constructor privat
public List<Bab> getBab() { return List.copyOf(bab); }
```

Tiga hal itulah yang membuatnya komposisi: **bagian dibuat di dalam induk**, **tidak ada jalan menerima objek bagian dari luar**, dan **getter tidak membocorkan koleksi**. Pada agregasi (`PerpustakaanService.tambahKoleksi(Koleksi k)`) objek bagian dibuat di luar, diterima lewat parameter, dan constructornya publik.

## 2. Bila objek induk pada komposisi dihapus, apa yang terjadi pada bagiannya? Tunjukkan di kode.
Satu-satunya rujukan ke daftar `bab` ada di dalam `Buku`. Begitu `Buku` tidak lagi dirujuk, `Bab` ikut tidak terjangkau dan dibersihkan *garbage collector*. Tidak ada jalan membuat `Bab` baru tanpa `Buku` (constructor privat), dan `Bab` tidak punya rujukan balik, sehingga di luar `Buku` ia tidak punya arti.

Catatan jujur: bila seseorang sempat menyimpan objek `Bab` dari `getBab()` ke variabel sendiri, objek itu secara teknis masih di memori, tetapi ia tidak bisa dipakai untuk apa pun yang berarti (tidak bisa dipindahkan ke buku lain karena tidak ada method yang menerimanya). Bandingkan dengan agregasi pada `Main`: `hapusKoleksi("SK-203")` mengembalikan objeknya dan objek itu tetap berguna, bahkan bisa dimasukkan kembali.

## 3. Mengapa getter untuk koleksi komposisi mengembalikan salinan? Apa yang terjadi kalau tidak?
Bukti ada di `percobaan/01-getter-bocor/`: bila `getBab()` mengembalikan `bab` apa adanya, `buku.getBab().clear()` dari luar berhasil dan buku berubah menjadi `{0 bab}`. Aturan `1..*`, penomoran, dan semua pemeriksaan milik `Buku` terlewati. Dengan `List.copyOf(...)`, `clear()` melempar `UnsupportedOperationException`. Uraian lengkap: `bukti-relasi.md`, bagian A.

## 4. Di diagram tertulis multiplicity `1..*`. Baris kode mana yang menjamin angka minimalnya satu?
Constructor `Buku` (`java/model/Buku.java`, dan `php/src/Model/Buku.php` untuk PHP):

```java
if (judulBab == null || judulBab.isEmpty()) {
    throw new IllegalArgumentException("Buku harus memiliki minimal satu bab");
}
```

Constructor menerima **daftar judul** (bukan objek `Bab`) dan membuat `Bab`-nya sendiri, sehingga komposisi tetap terjaga sekaligus minimal satu. Dari luar, `Buku` tanpa bab tidak pernah lahir, dan `getBab()` tidak bisa dipakai mengosongkannya. Tidak ada method `hapusBab`, jadi jumlahnya tidak bisa turun ke nol.

## 5. Ada kelas di diagram yang tidak ada di kode, atau sebaliknya? Periksa satu per satu.
Hasil pemeriksaan: tidak ada yang selisih.

| Di diagram (`uml/class.puml`) | Java | PHP |
|---|---|---|
| `Koleksi` (abstrak) | `model/Koleksi.java` | `src/Model/Koleksi.php` |
| `Buku`, `Majalah`, `Skripsi` | `model/Buku.java`, `Majalah.java`, `Skripsi.java` | `src/Model/...` |
| `Bab` | `Buku.Bab` (kelas bersarang) | `src/Model/Bab.php` |
| `StatusKoleksi` | `model/StatusKoleksi.java` (enum) | `src/Model/StatusKoleksi.php` (backed enum) |
| `JenisKeanggotaan` | `model/JenisKeanggotaan.java` (interface) | `src/Model/JenisKeanggotaan.php` |
| `Mahasiswa`, `Dosen`, `AnggotaKehormatan` | `model/...` | `src/Model/...` |
| `Anggota`, `Peminjaman` | `model/...` | `src/Model/...` |
| `PerpustakaanService` | `service/PerpustakaanService.java` | `src/Service/PerpustakaanService.php` |

Selisih kecil yang disengaja, dan siap dijelaskan:
- `Bab` bersarang di `Buku` pada Java, sehingga constructornya bisa privat. PHP tidak punya nested class, jadi `Bab` berdiri sendiri (lihat `bukti-relasi.md`, bagian A).
- Diagram tidak mencantumkan getter biasa (`getJudul()` dan sejenisnya) dan method privat pembantu (`ambilKoleksi()`, `jumlahPinjamanAktif()`, `buatBab()`); keduanya detail implementasi.
- Starter berisi `Jurusan` dan `Mahasiswa` sebagai pembanding agregasi. Keduanya **tidak dipakai**: pembandingnya diganti agregasi milik domain itu sendiri, yaitu `PerpustakaanService` — `Koleksi` dan `Anggota`, supaya setiap kelas di kode memang bagian dari diagram Sistem Perpustakaan.

## 6. Pada sequence diagram, objek mana yang memutuskan bahwa peminjaman ditolak? Mengapa bukan objek yang lain?
Ada dua penolak, masing-masing karena **hanya ia yang punya datanya**:
- **`Koleksi`** memutuskan lewat `dapatDipinjam()`: hanya ia yang tahu jenisnya (skripsi tidak boleh), statusnya (rusak atau hilang), dan stoknya. `PerpustakaanService` tidak perlu mengenal jenis koleksi satu per satu, sehingga tidak ada `instanceof` (sama seperti prinsip Open-Closed pertemuan 5 dan 6). `PerpustakaanService` hanya meneruskan penolakan itu sebagai exception.
- **`PerpustakaanService`** memutuskan batas tiga koleksi, karena aturan itu butuh data seluruh peminjaman, dan data itu bukan milik satu `Anggota` atau satu `Koleksi`.

Bukan `Peminjaman`, karena objek itu baru lahir **setelah** semua pemeriksaan lolos; menolak peminjaman yang belum ada tidak masuk akal.

## 7. Menambah aturan: satu anggota maksimum meminjam tiga buku. Di diagram muncul di mana? Di kode di mana?
Aturan ini sudah ada (R4 di `analisis.md`):
- **Diagram class:** konstanta `+ {static} BATAS_PINJAMAN_AKTIF : int = 3` pada `PerpustakaanService`.
- **Diagram sequence:** cabang alternatif "anggota sudah meminjam 3 koleksi" setelah pemeriksaan `dapatDipinjam()`.
- **Kode:** `PerpustakaanService.pinjam()`:

```java
if (jumlahPinjamanAktif(a) >= BATAS_PINJAMAN_AKTIF) {
    throw new IllegalStateException(a.getNama() + " sudah meminjam " + BATAS_PINJAMAN_AKTIF + " koleksi");
}
```

Bukti: pada `java Main`, pinjaman keempat Ani ditolak ("Ani Lestari sudah meminjam 3 koleksi"). Bila batas itu kelak berbeda tiap jenis keanggotaan, tempatnya pindah ke `JenisKeanggotaan.batasPinjam()`, dan `pinjam()` cukup memanggil `a.getJenis().batasPinjam()`.

Bila yang dimaksud "tiga **buku**" (bukan tiga koleksi), filternya ditambah pada `jumlahPinjamanAktif()` dengan memeriksa `p.getKoleksi() instanceof Buku`. Cara yang lebih bersih adalah menambahkan method abstrak pada `Koleksi` yang menjawab kategori hitungnya, agar `instanceof` tidak muncul.

---

## Saran riwayat commit
Modul meminta riwayat commit yang menunjukkan proses, bukan satu commit di akhir. Urutan yang masuk akal untuk repositori ini:

1. `Pert 7: analisis kebutuhan (analisis.md)`
2. `Pert 7: use case diagram`
3. `Pert 7: class diagram`
4. `Pert 7: sequence diagram peminjaman`
5. `Pert 7: model Java (Koleksi, Buku, Bab, Anggota, Peminjaman)`
6. `Pert 7: PerpustakaanService dan Main`
7. `Pert 7: padanan PHP`
8. `Pert 7: bukti-relasi dan percobaan`
9. `Pert 7: latihan mandiri dan tugas rumah (Sistem Presensi)`
