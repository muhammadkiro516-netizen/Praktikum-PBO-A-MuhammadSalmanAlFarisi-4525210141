# Penelusuran Dynamic Dispatch dan Open-Closed Principle
**Mata Kuliah:** Pemrograman Berorientasi Objek
**Materi:** Polimorfisme (Pertemuan 5)

Seluruh keluaran di bawah berasal dari kode pada folder `java/` (dijalankan dengan `javac *.java`, lalu `java Main`).

---

## Langkah 3: Menelusuri dynamic dispatch

Baris yang ditelusuri ada di perulangan pertama `Main.java`:

```java
for (BangunDatar b : daftar) {
    System.out.println("  " + b);
}
```

Catatan: pada kode starter, yang dipanggil adalah `println("  " + b)`, bukan `println(b)` secara langsung. Artinya `toString()` tidak dipanggil oleh `println`, tetapi oleh operasi penggabungan String (`"  " + b`).
Hal ini terlihat pada bytecode `Main`, yang memuat `invokedynamic makeConcatWithConstants` dengan argumen bertipe `BangunDatar`.

### Urutan pemanggilan untuk `b` = objek `Lingkaran(7)`

| No | Terjadi di | Yang dipanggil | Keterangan |
|---|---|---|---|
| 1 | `Main` | `"  " + b` (penggabungan String) | Operand `b` harus diubah menjadi String |
| 2 | Penggabungan String | `String.valueOf(b)` lalu `b.toString()` | Pemanggilan virtual. JVM melihat **kelas objek**, yaitu `Lingkaran` |
| 3 | `Lingkaran` | tidak ada `toString()` | `Lingkaran` tidak meng-override, jadi JVM naik ke induknya |
| 4 | `BangunDatar` | `BangunDatar.toString()` | Yang berjalan adalah milik induk, dengan `this` tetap menunjuk objek `Lingkaran` |
| 5 | `BangunDatar.toString()` | membaca field `nama` | Hasilnya `"Lingkaran"` |
| 6 | `BangunDatar.toString()` | `this.luas()` | **Dispatch**: JVM mencari `luas()` mulai dari kelas objek, ketemu `Lingkaran.luas()` |
| 7 | `Lingkaran` | `Lingkaran.luas()` | `Math.PI * 7 * 7` = 153,94 |
| 8 | `BangunDatar.toString()` | `this.keliling()` | Dispatch lagi, ketemu `Lingkaran.keliling()` = 43,98 |
| 9 | `BangunDatar.toString()` | `String.format(...)` | Menyusun teks, lalu dikembalikan ke penggabungan |
| 10 | `Main` | `PrintStream.println(String)` | Mencetak satu baris |

Untuk objek lain, langkah 1 sampai 5 dan 9 sampai 10 sama persis. Hanya langkah 6 sampai 8 yang berbeda:

| Objek di `daftar` | Langkah 6-7 | Langkah 8 |
|---|---|---|
| `Lingkaran(7)` | `Lingkaran.luas()` | `Lingkaran.keliling()` |
| `Persegi(5)` | `Persegi.luas()` | `Persegi.keliling()` |
| `Segitiga(3, 4, 5)` | `Segitiga.luas()` (rumus Heron) | `Segitiga.keliling()` |
| `Trapesium(4, 10, 4, 5, 5)` | `Trapesium.luas()` | `Trapesium.keliling()` |

Variabel `b` bertipe `BangunDatar` pada keempat putaran, tetapi method yang berjalan berbeda-beda.

### Bagaimana induk bisa memanggil method milik turunan?

Kompilator hanya memeriksa bahwa `luas()` **ada** di `BangunDatar` (sebagai method abstrak, yaitu kontrak). Ia tidak tahu dan tidak perlu tahu turunan mana yang akan muncul.
Bukti dari `javap -c -p BangunDatar`:

```text
  public java.lang.String toString();
    Code:
       8: aload_0
       9: getfield      #7     // Field nama:Ljava/lang/String;
      15: aload_0
      16: invokevirtual #15    // Method luas:()D
      25: aload_0
      26: invokevirtual #25    // Method keliling:()D
      33: invokestatic  #28    // Method java/lang/String.format:(...)
```

Instruksi `invokevirtual` berarti pemilihan method dilakukan **saat program berjalan**. JVM mengambil kelas asli dari objek `this` (yaitu `Lingkaran`), lalu mencari implementasi `luas()` mulai dari kelas itu naik ke induknya.
Induk tidak pernah menyebut nama turunan. Induk hanya bergantung pada kontrak (`luas()` dan `keliling()`), sedangkan isinya diisi oleh turunan. Mekanisme ini disebut *dynamic dispatch* atau *late binding*.

**Kesimpulan:** yang menentukan method mana yang dijalankan adalah **tipe objek** (kelas yang dibuat dengan `new`), bukan tipe variabel. Tipe variabel hanya menentukan method apa yang **boleh** dipanggil oleh kompilator. Variabel `BangunDatar` hanya boleh memanggil `luas()`, `keliling()`, `getNama()`, dan `toString()`, sedangkan `getJariJari()` baru bisa dipakai setelah downcasting `instanceof Lingkaran l`.

---

## Langkah 4: Membuktikan Open-Closed Principle

Kelas `Trapesium` ditambahkan sebagai berkas **baru**. Berikut kondisi berkas lama sesudah Trapesium ditambahkan (dibandingkan dengan berkas starter):

| Berkas lama | Isi diubah untuk menambah Trapesium? |
|---|---|
| `BangunDatar.java` | Tidak (identik dengan starter, dicek dengan `diff`) |
| `Lingkaran.java` | Tidak |
| `Persegi.java` | Tidak |
| `Segitiga.java` | Tidak |
| `Main.java` | Hanya **bertambah satu baris** objek di dalam array. Logika perulangan tidak disentuh |

**Jumlah berkas lama yang logikanya harus diubah: 0.**

Perbedaan `Main.java` starter dengan `Main.java` akhir (`diff starter/java/Main.java java/Main.java`):

```diff
14,15c14,15
<             // TODO Langkah 2: tambahkan new Segitiga(3, 4, 5) setelah kelasnya dibuat.
<             // TODO Langkah 4: tambahkan Trapesium setelah kelasnya dibuat.
---
>             new Segitiga(3, 4, 5),            // Langkah 2: satu baris tambahan
>             new Trapesium(4, 10, 4, 5, 5),    // Langkah 4: satu baris tambahan
```

Dua komentar TODO diganti dua baris objek. Dua perulangan `for (BangunDatar b : daftar)` dan `instanceof` untuk jari-jari tetap sama persis dengan starter.

Keluaran dengan empat bangun datar:

```text
=== Bangun Datar ===
  Lingkaran    luas=    153,94  keliling=     43,98
  Persegi      luas=     25,00  keliling=     20,00
  Segitiga     luas=      6,00  keliling=     12,00
  Trapesium    luas=     28,00  keliling=     24,00

  Total luas: 212,94
```

Pemeriksaan manual `Trapesium(4, 10, 4, 5, 5)`: luas = (4 + 10) / 2 x 4 = 28, keliling = 4 + 10 + 5 + 5 = 24.

---

## Langkah 5: AntiPattern versus AntiPatternRefaktor

Kedua versi memberi total luas yang sama:

```text
Total luas (cara anti-pattern): 184,94
Total luas (cara polimorfik): 184,94
```

(153,94 + 25,00 + 6,00 = 184,94.)

Versi refaktor tidak memuat satu pun `instanceof` (`grep -c instanceof AntiPatternRefaktor.java` menghasilkan 0).

### Perbandingan untuk menambah satu bangun datar baru (`TrapesiumData`)

Angka diperoleh dari `diff` antara berkas asli dan berkas yang sudah ditambah tipe baru (lihat `percobaan/06-tambah-tipe/`).

| Yang harus dilakukan | `AntiPattern.java` | `AntiPatternRefaktor.java` |
|---|---|---|
| Baris deklarasi tipe baru | 1 baris (`record`) | 3 baris (`record` beserta `luas()`) |
| Baris yang **disisipkan ke logika lama** (`hitungLuas`) | **2 baris** (cabang `else if` dan `return`) | **0 baris** |
| Baris di array `daftar` | 2 baris (1 diubah menambah koma, 1 ditambah) | 2 baris (sama) |
| Method lama yang harus dibuka dan disunting | 1 (`hitungLuas`) | 0 |

Jawaban untuk pertanyaan "berapa baris yang harus disunting": pada versi anti-pattern ada **2 baris di dalam method lama** (ditambah deklarasi tipe dan satu baris data). Pada versi refaktor **0 baris pada kode yang sudah ada**; yang terjadi hanya menambah tipe baru dan mendaftarkannya.

### Jawaban pertanyaan pemandu di `AntiPattern.java`

1. **Berapa baris yang harus disunting untuk menambah satu bangun datar baru?** Lihat tabel di atas: 2 baris di dalam `hitungLuas()`, dan setiap bangun baru akan memperpanjang rantai itu.
2. **Apa yang terjadi jika seseorang lupa menambahkan satu cabang else-if?** Kompilator tidak memberi peringatan apa pun karena parameternya bertipe `Object`. Kesalahan baru muncul saat program berjalan, ketika objek itu diproses (hasil percobaan):
   ```text
   Exception in thread "main" java.lang.IllegalArgumentException: Bangun tidak dikenal: TrapesiumData[a=4.0, b=10.0, t=4.0]
   	at AntiPattern.hitungLuas(AntiPattern.java:29)
   ```
   Pada versi polimorfik, lupa mengimplementasikan `luas()` langsung ditolak kompilator:
   ```text
   AntiPatternRefaktor.java:29: error: TrapesiumData is not abstract and does not override abstract method luas() in Bangun
   ```
3. **Di mana pengetahuan "cara menghitung luas lingkaran" seharusnya berada?** Di dalam tipe lingkaran itu sendiri (`LingkaranData.luas()`), karena dialah yang punya data jari-jari. Fungsi di luar tidak perlu tahu rumusnya.

Kesimpulan: pada versi anti-pattern, pengetahuan tentang semua tipe terkumpul di satu method pusat, sehingga setiap tipe baru memaksa method itu dibuka lagi (melanggar Open-Closed Principle). Pada versi polimorfik, pengetahuan itu tersebar ke tipe masing-masing sehingga kode lama tidak tersentuh.
