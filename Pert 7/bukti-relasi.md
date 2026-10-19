# Bukti Relasi: Komposisi dan Agregasi Benar-Benar Terwujud di Kode
**Pertemuan 7 — Langkah 6**

Semua keluaran di bawah disalin dari hasil menjalankan kode di repositori ini (`java Main` di folder `java/`, `php main.php` di folder `php/`, dan program kecil di folder `percobaan/`).

---

## A. Komposisi `Buku` memiliki `Bab`

Tiga syarat yang diuji: (1) koleksi internal tidak bisa diubah dari luar, (2) `Bab` tidak bisa dibuat dari luar, (3) multiplicity `1..*` dijaga.

### Percobaan 1: mengubah koleksi internal dari luar

**Sebelum diperbaiki** (`percobaan/01-getter-bocor/`, `getBab()` mengembalikan `bab` apa adanya):

```java
buku.getBab().clear();   // mengubah koleksi internal DARI LUAR
```

```text
Sebelum : PBO-001  Pemrograman Berorientasi Objek         A. W. Pribadi  2024 [3 eks, Tersedia] {3 bab}
Sesudah : PBO-001  Pemrograman Berorientasi Objek         A. W. Pribadi  2024 [3 eks, Tersedia] {0 bab}
Jumlah bab menurut buku.getBab(): 0
MASALAH: bab dikosongkan dari luar, aturan 1..* jebol tanpa lewat Buku.
```

Bab habis dikosongkan tanpa satu pun pemeriksaan milik `Buku` ikut berjalan. Diagram boleh menulis `*--` dan `1..*`, tetapi kodenya sudah bukan komposisi.

**Sesudah diperbaiki** (`java/model/Buku.java`, `return List.copyOf(bab);`), potongan keluaran `java Main`:

```text
  Mencoba mengubah koleksi internal dari luar...
    Ditolak - koleksi bab tidak bisa diubah dari luar. Benar.
```

`List.copyOf(...)` menghasilkan daftar tak-berubah, sehingga `clear()` melempar `UnsupportedOperationException`.

**Mengapa getter mengembalikan salinan?** Tanpa salinan, pemegang referensi di luar bisa menambah, menghapus, atau mengosongkan bab. Aturan `1..*` dan penomoran bab yang dijaga `Buku` lalu bisa dilewati begitu saja. Salinan tak-berubah menutup pintu itu: satu-satunya jalan mengubah bab tetap `tambahBab()`.

### Percobaan 2: membuat `Bab` dari luar `Buku`

`percobaan/03-bab-dari-luar/Main.java`:

```java
Buku.Bab liar = new Buku.Bab(9, "Bab liar");   // <- konstruktor Bab privat
```

Pesan kompilator:

```text
./Main.java:9: error: Bab(int,String) has private access in Bab
        Buku.Bab liar = new Buku.Bab(9, "Bab liar");   // <- konstruktor Bab privat
                        ^
1 error
```

Penolakan terjadi saat **kompilasi**, jadi tidak bergantung pada data uji.

### Percobaan 3: multiplicity `1..*`

Potongan keluaran `java Main`:

```text
  Multiplicity 1..*: buku tanpa bab ditolak constructor
    Ditolak: Buku harus memiliki minimal satu bab
```

Baris yang menjamin angka minimalnya satu ada di constructor `Buku`:

```java
if (judulBab == null || judulBab.isEmpty()) {
    throw new IllegalArgumentException("Buku harus memiliki minimal satu bab");
}
```

### Padanan PHP dan catatan yang jujur

Potongan keluaran `php main.php`:

```text
  Mencoba mengubah koleksi internal dari luar...
    Larik pemanggil dikosongkan, bab di dalam buku masih: 3
    Mengubah isi Bab ditolak: Cannot modify readonly property Perpustakaan\Model\Bab::$judul
```

- PHP mengembalikan `array` **by-value**, sehingga pemanggil hanya mengosongkan salinan larik-nya. Koleksi internal tetap tiga bab.
- Objek `Bab` di dalam larik **tetap berbagi referensi**. Karena itu `Bab` dibuat `final` dengan properti `readonly`: mengubah isinya melempar `Error`.
- **Kelemahan PHP:** tidak ada nested class, jadi bahasa tidak bisa melarang `new Bab(...)` dari luar. Buktinya (`percobaan/04-php-bab-dari-luar/bab-liar.php`):

```text
Bab dibuat dari luar Buku tanpa galat: Bab 9: Bab liar
```

Bab liar itu memang tidak masuk ke dalam `Buku` mana pun (tidak ada method yang menerimanya), tetapi objeknya bisa lahir sendirian. Disiplin "hanya Buku yang membuat Bab" di PHP dijaga lewat **konvensi** (anotasi `@internal`), sedangkan di Java ditegakkan oleh kompilator.

---

## B. Agregasi `PerpustakaanService` menaungi `Koleksi` dan `Anggota`

Objek bagian dibuat di luar, diterima lewat `tambahKoleksi(k)` dan `daftarkanAnggota(a)`. Bukti objeknya tetap hidup setelah dilepas, potongan keluaran `java Main`:

```text
  Mengeluarkan skripsi dari katalog...
    Katalog sekarang berisi 3 koleksi
    Objeknya tetap hidup di luar layanan: Sistem Informasi Perpustakaan
    Dimasukkan kembali, katalog berisi 4 koleksi
```

Kodenya:

```java
Koleksi dilepas = layanan.hapusKoleksi("SK-203");   // dikeluarkan dari katalog
dilepas.getJudul();                                  // objeknya masih bisa dipakai
layanan.tambahKoleksi(dilepas);                      // dan bisa dimasukkan lagi
```

`hapusKoleksi()` hanya menghapus **rujukan** di daftar milik layanan dan mengembalikan objeknya. Itulah beda dengan komposisi: `Bab` yang "dilepas" dari `Buku` tidak punya jalan keluar sama sekali.

Padanan PHP menghasilkan keluaran yang sama persis, kecuali bagian "mengubah koleksi internal dari luar" yang dijelaskan pada bagian A.

### Perbedaan ringkas

| | Komposisi (`Buku` – `Bab`) | Agregasi (`PerpustakaanService` – `Koleksi`) |
|---|---|---|
| Siapa yang membuat bagian | Induk, di dalam | Pihak luar |
| Method penerima | `tambahBab(nomor, judul)`, tanpa objek `Bab` | `tambahKoleksi(Koleksi)`, menerima objek |
| Getter | `List.copyOf(...)` | `List.copyOf(...)` (daftar saja; objeknya tetap milik bersama) |
| Setelah dilepas | Tidak ada jalan keluar | `hapusKoleksi()` mengembalikan objeknya |
| Constructor bagian | `private` | `public` |
