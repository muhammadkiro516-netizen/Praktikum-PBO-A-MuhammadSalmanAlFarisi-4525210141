# Hasil Latihan Mandiri di Lab
**Materi:** Pemodelan relasi objek dan UML (Pertemuan 7)

---

## E1. Mengubah satu agregasi menjadi komposisi

**Yang dikerjakan:** relasi `PerpustakaanService` — `Koleksi` yang semula **agregasi** diubah menjadi **komposisi**. Salinan kode ada di [percobaan/02-agregasi-jadi-komposisi/](percobaan/02-agregasi-jadi-komposisi/), sehingga berkas di `java/` tidak berubah.

Agar benar-benar komposisi, layanan harus membuat sendiri objek bagiannya dan tidak boleh lagi menerimanya dari luar. Pada percobaan ini diwakili `Majalah`:

```java
// SEBELUM (agregasi): objek datang dari luar
public void tambahKoleksi(Koleksi k) {
    ...
    koleksi.add(k);
}
public Koleksi hapusKoleksi(String nomorInduk) { ...; return k; }

// SESUDAH (komposisi): layanan sendiri yang membuat objek
public void tambahMajalah(String nomorInduk, String judul, String penulis, int tahun, int eksemplar) {
    ...
    koleksi.add(new Majalah(nomorInduk, judul, penulis, tahun, eksemplar));
}
public void hapusKoleksi(String nomorInduk) { ... }   // tidak lagi mengembalikan objek
```

**Hasil** (`java Main` di folder percobaan):

```text
Katalog: 1 koleksi
Setelah dihapus: 0 koleksi
Objek Majalah tidak punya jalan keluar untuk tetap hidup di luar: hapusKoleksi() kini void.
```

**Berapa baris yang berubah?** Pada `PerpustakaanService.java` hasil `diff`: **9 baris ditambah dan 12 baris dihapus** (termasuk komentar dan satu `import`). Perubahan itu hanya untuk SATU jenis koleksi:

| Yang berubah | Jumlah |
|---|---|
| `tambahKoleksi(Koleksi)` diganti `tambahMajalah(...)` | 1 method |
| `hapusKoleksi()` tidak lagi mengembalikan objek (`return k;` dihapus, tipe kembalian menjadi `void`) | 1 method |
| Pemanggil di `Main` | seluruh pemanggilan berubah dari `new Majalah(...)` + `tambahKoleksi(...)` menjadi satu panggilan `tambahMajalah(...)` |

**Mengapa sebanyak itu, dan mengapa tidak masuk akal di sini?**
- Komposisi menuntut tiga jalan pembuatan (`tambahBuku`, `tambahMajalah`, `tambahSkripsi`), dan jenis koleksi baru memaksa menyunting layanan. Itu melanggar Open-Closed yang dipertahankan dengan `Koleksi` abstrak.
- Koleksi tidak bisa lagi "dipindahkan" atau "dipinjamkan ke perpustakaan lain" karena objeknya terkunci di dalam layanan.
- Catatan jujur: percobaan ini belum komposisi sepenuhnya, karena `getKoleksi()` masih mengembalikan objek `Koleksi` asli (daftarnya salinan, tetapi objeknya bisa dipanggil `kurangiStok()` dari luar). Menutupnya butuh antarmuka baca-saja (*read-only view*), yaitu perubahan lebih besar lagi.

**Kesimpulan:** mengubah relasi di diagram hanya mengganti `o--` menjadi `*--`, tetapi di kode perubahannya menjalar ke cara objek lahir, cara keluar, dan semua pemanggilnya. Itulah mengapa derajat keterikatan harus diputuskan **sebelum** menulis kode. Untuk koleksi, agregasi adalah pilihan yang benar.

---

## E2. Activity diagram: pengembalian buku dengan cabang denda

File: [uml/activity-pengembalian.puml](uml/activity-pengembalian.puml) (render: `activity-pengembalian.png` dan `.svg`).

Alur yang digambarkan, sama dengan `PerpustakaanService.kembalikan()`:
1. Cari peminjaman aktif anggota untuk koleksi itu. Bila tidak ada, tolak.
2. Catat tanggal kembali dan tambah stok satu eksemplar.
3. Hitung hari terlambat `maks(0, tanggal kembali − jatuh tempo)`.
4. **Cabang denda:** bila terlambat, denda = hari terlambat × denda per hari menurut jenis keanggotaan (Rp1.000, Rp500, bebas, atau jenis lain lewat `dendaPerHari()`). Bila tidak terlambat, denda Rp0.

**Pemeriksaan manual dengan keluaran `java Main`:**

| Anggota | Koleksi | Jatuh tempo | Kembali | Terlambat | Denda per hari | Denda |
|---|---|---|---|---|---|---|
| Ani (Mahasiswa) | Buku 14 hari, pinjam 1 Okt | 15 Okt | 20 Okt | 5 hari | Rp1.000 | Rp5.000 |
| Budi (Dosen) | Majalah 3 hari, pinjam 1 Okt | 4 Okt | 14 Okt | 10 hari | Rp500 | Rp5.000 |
| Cici (Kehormatan) | Majalah, pinjam 1 Okt | 4 Okt | 14 Okt | 10 hari | Rp0 | Rp0 |
| Dedi (Alumni, jenis baru) | Majalah, pinjam 1 Okt | 4 Okt | 14 Okt | 10 hari | Rp750 | Rp7.500 |
