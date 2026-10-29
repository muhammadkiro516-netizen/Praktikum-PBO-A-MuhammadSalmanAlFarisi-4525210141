import java.util.List;

public class Main {

    /**
     * Checkpoint Langkah 6: satu fungsi memproses ketiga jenis koleksi
     * tanpa SATU PUN pemeriksaan tipe (tidak ada instanceof).
     * Skripsi tidak perlu dikecualikan dengan if, karena ia sendiri yang menjawab false.
     */
    static String ringkas(Peminjamable p) {
        return p.bolehDipinjam()
            ? "boleh dipinjam " + p.masaPinjamHari() + " hari"
            : "tidak boleh dipinjam (baca di tempat)";
    }

    public static void main(String[] args) {
        // Tipe variabel adalah kontraknya (Peminjamable), bukan Buku/Majalah/Skripsi.
        List<Peminjamable> daftar = List.of(
            new Buku("B-001", "Pemrograman Berorientasi Objek"),
            new Majalah("M-014", "Majalah Informatika Edisi September"),
            new Skripsi("S-203", "Sistem Informasi Perpustakaan")
        );

        System.out.println("=== Peminjamable ===");
        for (Peminjamable p : daftar) {
            // toString() milik Object tetap bisa dipanggil lewat tipe interface,
            // dan yang berjalan adalah toString() dari Koleksi (dynamic dispatch).
            System.out.println("  " + p + "  -> " + ringkas(p));
        }

        System.out.println();
        System.out.println("=== StatusPinjam ===");
        for (StatusPinjam s : StatusPinjam.values()) {
            System.out.printf("  %-10s %s%n", s, s.keterangan());
        }
    }
}
