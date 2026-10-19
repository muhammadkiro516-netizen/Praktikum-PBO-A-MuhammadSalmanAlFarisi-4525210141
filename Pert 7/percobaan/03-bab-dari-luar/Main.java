import model.Buku;
import java.util.List;

/** Percobaan 3: mencoba membuat Bab dari luar Buku. HARUS gagal dikompilasi. */
public class Main {
    public static void main(String[] args) {
        Buku buku = new Buku("PBO-001", "Pemrograman Berorientasi Objek", "A. W. Pribadi", 2024, 3,
                List.of("Paradigma Objek"));
        Buku.Bab liar = new Buku.Bab(9, "Bab liar");   // <- konstruktor Bab privat
        System.out.println(liar);
    }
}
