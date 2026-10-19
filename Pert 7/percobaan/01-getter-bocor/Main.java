import model.Buku;
import java.util.List;

/** Percobaan 1 bukti-relasi.md: getBab() mengembalikan koleksi internal apa adanya. */
public class Main {
    public static void main(String[] args) {
        Buku buku = new Buku("PBO-001", "Pemrograman Berorientasi Objek", "A. W. Pribadi", 2024, 3,
                List.of("Paradigma Objek", "Enkapsulasi", "Pewarisan dan Polimorfisme"));
        System.out.println("Sebelum : " + buku);

        buku.getBab().clear();   // mengubah koleksi internal DARI LUAR

        System.out.println("Sesudah : " + buku);
        System.out.println("Jumlah bab menurut buku.getBab(): " + buku.getBab().size());
        System.out.println(buku.getBab().isEmpty()
            ? "MASALAH: bab dikosongkan dari luar, aturan 1..* jebol tanpa lewat Buku."
            : "Aman.");
    }
}
