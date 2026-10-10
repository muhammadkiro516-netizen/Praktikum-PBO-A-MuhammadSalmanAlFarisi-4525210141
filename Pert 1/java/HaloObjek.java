/**
 * Sesi 1 — objek pertama.
 *
 * Kompilasi dan jalankan:
 *     javac -d out HaloObjek.java
 *     java -cp out HaloObjek
 */
public class HaloObjek {

    // Atribut dibuat private: data objek tidak seharusnya terbuka bagi siapa pun.
    private String nama;
    private String nim;

    public HaloObjek(String nama, String nim) {
        // Parameter punya nama yang sama dengan atribut,
        // sehingga `this` dipakai untuk membedakan atribut milik objek.
        this.nama = nama;
        this.nim = nim;
    }

    /**
     * Mengembalikan satu baris sapaan yang disusun dari ATRIBUT objek,
     * sehingga setiap objek menyapa dengan nama dan NIM miliknya sendiri.
     */
    public String sapa() {
        return "Halo, saya " + nama + " (" + nim + ")";
    }

    public static void main(String[] args) {
        HaloObjek saya = new HaloObjek("Muhammad Salman Al Farisi", "4525210141");
        System.out.println(saya.sapa());

        // Bukti bahwa objek menyimpan datanya masing-masing:
        HaloObjek temanSekelas = new HaloObjek("Budi Santoso", "2024002");
        System.out.println(temanSekelas.sapa());

        System.out.println();
        System.out.println("Pertanyaan untuk direnungkan:");
        System.out.println("  Baris mana di atas yang MEMBUAT objek?");
        System.out.println("  Baris mana yang hanya MENDEKLARASIKAN kelas?");
        System.out.println("  Mengapa kedua objek di atas bisa menyapa dengan nama berbeda,");
        System.out.println("  padahal method sapa() hanya ditulis satu kali?");
    }
}
