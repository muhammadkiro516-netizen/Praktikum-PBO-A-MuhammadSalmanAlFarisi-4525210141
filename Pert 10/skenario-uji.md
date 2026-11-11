# Skenario Kegagalan — Sistem Perpustakaan

Tabel ini diisi berdasarkan hasil menjalankan `Main.java` dan `main.php`.
Setiap baris telah dicoba langsung; bukan sekadar dibayangkan.

| No | Masukan / tindakan | Kegagalan yang diharapkan | Jenis exception | Pesan yang ditampilkan |
|----|--------------------|---------------------------|-----------------|------------------------|
| 1  | Pinjam ISBN yang tidak terdaftar (misal `"ISBN-TIDAK-ADA"`) | Buku tidak ditemukan | `BukuTidakDitemukanException` | `Buku dengan ISBN ISBN-TIDAK-ADA tidak ditemukan.` |
| 2  | Pinjam dengan ID anggota yang belum didaftarkan (misal `"TIDAK_ADA"`) | Anggota tidak ditemukan | `AnggotaTidakDitemukanException` | `Anggota dengan ID TIDAK_ADA tidak ditemukan.` |
| 3  | Pinjam buku yang stoknya sudah habis (semua eksemplar sedang dipinjam orang lain) | Semua eksemplar sedang dipinjam | `BukuSedangDipinjamException` | `Semua eksemplar "The C Programming Language" (ISBN 978-0-13-110362-7) sedang dipinjam.` |
| 4  | Anggota yang sudah meminjam 3 buku mencoba meminjam lagi | Batas pinjam tercapai | `BatasPinjamTerlampauiException` | `Anggota A001 telah mencapai batas pinjam maksimum (3 koleksi).` |
| 5  | Memasukkan ISBN kosong `""` di mode interaktif | Validasi input awal, tidak sampai ke layanan | *(tidak ada exception)* | `[!] ISBN dan ID tidak boleh kosong.` |
| 6  | Memasukkan ID anggota kosong `""` di mode interaktif | Validasi input awal, tidak sampai ke layanan | *(tidak ada exception)* | `[!] ISBN dan ID tidak boleh kosong.` |
| 7  | Memasukkan hanya spasi `"   "` sebagai ISBN (setelah `.trim()`) | Sama dengan ISBN kosong | *(tidak ada exception, ditolak di validasi)* | `[!] ISBN dan ID tidak boleh kosong.` |
| 8  | Pinjam buku yang terdaftar, stok masih ada, anggota valid | Peminjaman berhasil | *(tidak ada exception)* | `[OK] A001 meminjam ISBN 978-0-13-468599-1` |
| 9  | Ekspor daftar buku ke path yang tidak bisa ditulis (misal path read-only) | File tidak bisa dibuka | `IOException` (Java) / `RuntimeException` (PHP) | `Gagal ekspor: <pesan OS>` |
| 10 | Pinjam buku yang sama dua kali oleh anggota yang sama tanpa mengembalikan | Pada percobaan ke-2, stok buku itu sudah berkurang; bila stok awal 1, exception stok habis | `BukuSedangDipinjamException` | `Semua eksemplar "..." sedang dipinjam.` |
| 11 | Anggota A002 meminjam buku yang stoknya 2; anggota A001 sudah meminjam salah satunya | Peminjaman berhasil (stok masih 1) | *(tidak ada exception)* | `[OK] A002 meminjam ISBN 978-0-13-468599-1` |
| 12 | Pinjam ISBN yang isinya angka saja, misal `"12345"` (tidak terdaftar) | Buku tidak ditemukan | `BukuTidakDitemukanException` | `Buku dengan ISBN 12345 tidak ditemukan.` |
| 13 | Daftarkan anggota baru lalu langsung pinjam buku valid bertahap hingga batas ke-3 | Ke-3 berhasil; ke-4 gagal | `BatasPinjamTerlampauiException` pada percobaan ke-4 | `Anggota A001 telah mencapai batas pinjam maksimum (3 koleksi).` |

---

## Jawaban Pertanyaan

### 1. Kesalahan pengguna vs kesalahan pemrogram

| Skenario | Kategori | Alasan |
|---|---|---|
| Skenario 1–4, 10, 12 | **Kesalahan pengguna** (user/domain error) | Masukan tidak valid secara bisnis: ID/ISBN salah, stok habis, batas terlampaui. Ini adalah kondisi yang wajar terjadi saat runtime. |
| Skenario 5–7 | **Kesalahan pengguna** (input validation) | Pengguna tidak mengisi data wajib. |
| Skenario 9 | **Kesalahan lingkungan** (resource error) | Sistem berkas tidak bisa ditulis; bukan salah pengguna maupun pemrogram, tapi keadaan luar. |
| Memanggil `pinjam()` sebelum `daftarkanAnggota()` dipanggil sama sekali | **Kesalahan pemrogram** | Ini seharusnya tidak terjadi bila kode klien ditulis dengan benar. |

Perbedaan ini memengaruhi jenis exception: kesalahan domain yang wajar terjadi di runtime (pengguna/bisnis) cocok sebagai **unchecked exception** (turunan `RuntimeException`) karena tidak perlu kompilator memaksa setiap pemanggil menanganinya. Kesalahan lingkungan eksternal seperti I/O cocok sebagai **checked exception** (`IOException`) karena pemanggil memang *harus* mempertimbangkannya.

### 2. Mengapa `PerpustakaanException` diturunkan dari `RuntimeException`

`PerpustakaanException` diturunkan dari `RuntimeException` (**unchecked**) karena:

- Kegagalan domain perpustakaan (anggota tidak ditemukan, stok habis, batas terlampaui) adalah kondisi *yang diharapkan bisa terjadi* saat runtime, bukan anomali eksternal seperti putusnya koneksi jaringan.
- Kompilator Java tidak perlu memaksa setiap pemanggil `pinjam()` untuk mendeklarasikan `throws`. Hal itu hanya menambah boilerplate dan mempersulit penggunaan di ekspresi lambda atau stream.
- Pola ini sesuai dengan *Item 70: Favor the use of standard exceptions* dan *Item 72: Favor the use of unchecked exceptions* (Effective Java, Bloch): gunakan unchecked untuk kesalahan pemrograman atau pelanggaran kontrak domain; gunakan checked hanya untuk kondisi yang *wajar dan perlu ditangani oleh pemanggil* (misalnya, `IOException` pada operasi berkas).
- Di PHP tidak ada perbedaan checked/unchecked; semua exception bersifat unchecked secara alami.

### 3. Apa yang terjadi kalau catch kosong dibuat dengan sengaja

Ketika blok `catch` dikosongkan seperti ini:

```java
try {
    layanan.pinjam(id, isbn);
} catch (PerpustakaanException e) {
    // kosong sengaja
}
```

Program tidak berhenti dan tidak menampilkan pesan apa pun. Exception "ditelan" begitu saja. Dari sudut pandang pengguna, operasi gagal tanpa penjelasan — persis seperti `return false` di `LibraryServiceKotor`. Hal ini berbahaya karena:

1. Pengguna tidak tahu apakah operasi berhasil atau gagal.
2. Pemrogram tidak mendapat sinyal untuk memperbaiki kode.
3. Bug tersembunyi dan sulit dilacak.

Pelajaran: catch kosong hanya boleh ada bila ada alasan sangat spesifik yang didokumentasikan. Minimal tulis `System.err.println(e.getMessage())` atau lempar kembali exception-nya.
