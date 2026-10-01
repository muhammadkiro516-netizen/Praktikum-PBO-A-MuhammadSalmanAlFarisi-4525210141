# Analisis Invariant - Kelas Mahasiswa

Invariant adalah kondisi yang harus selalu benar selama objek `Mahasiswa` ada.
Daftar ini ditulis sebelum kode dilengkapi, lalu setiap poin dipetakan ke tempat penjagaannya.

## Daftar Invariant
| No | Invariant | Dijaga oleh |
|---|---|---|
| 1 | NIM tidak pernah kosong atau null | Pemeriksaan di constructor, melempar `IllegalArgumentException` (Java) / `InvalidArgumentException` (PHP) |
| 2 | NIM tidak berubah setelah mahasiswa terdaftar | Atribut `final` (Java) / `readonly` (PHP), dan tidak ada `setNim()` |
| 3 | Nama tidak berubah setelah objek dibuat | Atribut `final` / `readonly`, tanpa setter |
| 4 | Setiap komponen nilai (tugas, UTS, UAS) berada di rentang 0 sampai 100 | Method privat `pastikanNilaiSah(...)` dipanggil dari constructor untuk ketiga komponen |
| 5 | Bobot nilai selalu 30%, 30%, 40% (total 100%) | Konstanta `BOBOT_TUGAS`, `BOBOT_UTS`, `BOBOT_UAS`, bukan angka literal di dalam method |
| 6 | Nilai akhir selalu konsisten dengan komponen nilainya | Nilai akhir tidak disimpan sebagai atribut, tetapi dihitung ulang oleh `nilaiAkhir()` |
| 7 | Huruf mutu selalu konsisten dengan nilai akhir | `hurufMutu()` dihitung dari `nilaiAkhir()`, tidak disimpan |

## Keputusan Desain
- **Mengapa objek ditolak sejak constructor?** Objek yang sudah terbentuk dijamin sah. Kode lain tidak perlu memeriksa ulang apakah nilainya masuk akal.
- **Mengapa `pastikanNilaiSah(...)` dibuat sebagai method pembantu?** Aturan rentang 0-100 sama untuk tiga komponen. Dengan satu method, aturan hanya ditulis sekali dan pesan kesalahannya menyebut komponen yang bermasalah (misalnya "nilai tugas harus berada dalam rentang 0 sampai 100.").
- **Mengapa nilai akhir tidak disimpan?** Kalau disimpan, ia bisa tidak sinkron dengan komponen nilainya. Dihitung saat dibutuhkan, ia selalu benar.
- **Mengapa tidak ada `setNim()`?** Invariant nomor 2 melarangnya. Menambah setter akan membuka celah untuk mengubah identitas mahasiswa.
- **Catatan:** `nilaiTugas`, `nilaiUts`, dan `nilaiUas` tidak dibuat `final`, tetapi sampai sekarang tidak ada setter yang mengubahnya. Jika suatu saat ditambah setter nilai, setter itu wajib memanggil `pastikanNilaiSah(...)` agar invariant nomor 4 tetap terjaga.
