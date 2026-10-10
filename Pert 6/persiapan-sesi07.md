# Persiapan Sesi 7: Daftar Kelas Sistem Perpustakaan
**Tugas rumah 2 (pertemuan 6):** menuliskan daftar kelas yang menurut saya diperlukan untuk Sistem Perpustakaan.

> **Catatan:** modul sesi 7 belum ada di berkas yang saya terima, sehingga daftar ini disusun dari apa yang sudah dibangun di pertemuan 5 dan 6 (`Koleksi`, `Buku`, `Majalah`, `Skripsi`, `Peminjamable`, `StatusPinjam`, serta `biayaKeterlambatan()`). Setelah membaca deskripsi sistem di modul sesi 7, daftar ini perlu dicocokkan ulang.

## Kelas, interface, dan enum yang diperkirakan perlu

| Jenis | Nama | Tanggung jawab | Sudah ada? |
|---|---|---|---|
| abstract class | `Koleksi` | Identitas bersama: kode dan judul | Ya (`tugas-rumah/` p5 dan `latihan/` p6) |
| class | `Buku`, `Majalah`, `Skripsi` | Jenis koleksi dengan aturan masa pinjam dan denda sendiri | Ya |
| interface | `Peminjamable` | Kontrak `bolehDipinjam()` dan `masaPinjamHari()` | Ya (`latihan/`) |
| enum | `StatusPinjam` | `Tersedia`, `Dipinjam`, `Terlambat`, `Hilang` | Ya (`latihan/`) |
| class | `Anggota` | Identitas peminjam (nomor anggota, nama), batas jumlah pinjaman | Belum |
| class | `Peminjaman` | Mencatat satu transaksi: anggota, koleksi, tanggal pinjam, tenggat, tanggal kembali | Belum |
| class | `Perpustakaan` | Menyimpan daftar koleksi dan anggota, serta mengoordinasi `pinjam()` dan `kembalikan()` | Belum |
| interface | `Dendaable` (nama sementara) | Kontrak `biayaKeterlambatan(int hari)`, bila tidak cukup diletakkan di `Koleksi` | Sebagian (method sudah ada di `Koleksi` p5) |
| class | `PeminjamanException` (opsional) | Galat khusus: koleksi tidak boleh dipinjam, anggota melebihi batas | Belum |

## Hubungan antar kelas (sementara)
- `Perpustakaan` **memiliki** banyak `Koleksi` dan banyak `Anggota` (komposisi).
- `Peminjaman` **menghubungkan** satu `Anggota` dengan satu `Koleksi`.
- `Perpustakaan.pinjam()` memeriksa `Peminjamable.bolehDipinjam()` tanpa `instanceof`, sama seperti fungsi `ringkas()` di `latihan/`.

## Pertanyaan yang perlu dijawab setelah membaca modul sesi 7
1. Apakah denda tetap dihitung di `Koleksi` atau dipisah ke interface sendiri?
2. Siapa yang menyimpan `StatusPinjam`: `Koleksi` atau `Peminjaman`?
3. Apakah kelas `Anggota` punya jenis (mahasiswa, dosen) yang aturan pinjamnya berbeda? Bila ya, itu kandidat polimorfisme baru.
