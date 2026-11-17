# Persiapan Demo Pertemuan 11

1. **Mengapa buku disimpan di `Map`, bukan `List`?** Pencarian lewat ISBN pada `List` memeriksa item satu per satu, sedangkan `Map` langsung ke kuncinya (`get(isbn)`), sehingga tetap cepat walau bukunya ribuan.
2. **Apa gunanya generics?** Memberi label tipe pada wadah. `Repository<Buku>` menolak `Anggota` saat **kompilasi**; tanpa generics, kesalahan baru muncul saat jalan sebagai `ClassCastException`. Bukti galat kompilasinya ada di [pilihan-koleksi.md](pilihan-koleksi.md).
3. **Apa arti `T` pada `Repository<T>`?** Type parameter: tipe yang baru ditentukan saat kelasnya dipakai. Cara bacanya sama dengan `JpaRepository<Buku, Long>` pada Spring Data JPA (sesi 14).
4. **Mengapa `Optional` lebih baik dari `null`?** Kasus kosong dipaksa ditangani oleh pemanggil, sehingga tidak ada `NullPointerException` kejutan. Padanan PHP: `?->` untuk melanjutkan bila tidak null dan `??` untuk nilai cadangan.
5. **filter, map, reduce, groupingBy.** Menyebut *apa* yang diinginkan tanpa menulis `for`. Pada `LaporanBuku`: `filter(Buku::tersedia)`, `map(Buku::getJudul)` lalu `joining`, dan `groupingBy(Buku::getPenulis, ..., counting())`.
6. **Pesan jebakan PHP.** `array_map(fungsi, array)` tetapi `array_filter(array, fungsi)`, dan hasil `array_filter` harus dibungkus `array_values` agar indeksnya tidak berlubang.
7. **PECS.** *Producer Extends, Consumer Super*: `List<? extends Buku>` hanya untuk dibaca, `List<? super Buku>` hanya untuk ditambah.
8. **Apa yang dilakukan `@BeforeEach`?** Dijalankan sebelum **setiap** uji, sehingga tiap uji berangkat dari keadaan bersih dan tidak saling memengaruhi.
9. **PHP tidak punya generics.** Padanannya: anotasi PHPDoc (dibaca IDE/PHPStan), typed collection (`KoleksiBuku`), dan union type. Salah tipe baru ketahuan saat jalan (`TypeError`).
