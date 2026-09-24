# Catatan Implementasi

`PegawaiKontrak` tidak perlu melakukan overriding terhadap `hitungGaji()`.
Pegawai kontrak memang tidak memperoleh tunjangan masa kerja, sehingga rumus
gaji dasar dari `Pegawai` sudah tepat: gaji kontrak sama dengan gaji pokok.
Method tersebut tetap diwariskan agar perilaku yang sama tidak ditulis ulang.

Sebaliknya, `PegawaiTetap`, `Dosen`, dan `PegawaiHarian` melakukan overriding
karena masing-masing memiliki komponen perhitungan tambahan yang berbeda.