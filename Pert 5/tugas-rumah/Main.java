import java.util.Locale;

/** Program uji tugas rumah 2. Perulangan memanggil biayaKeterlambatan() tanpa memeriksa tipe. */
public class Main {

    private static String rupiah(double nilai) {
        return String.format(Locale.forLanguageTag("id-ID"), "Rp%,.2f", nilai);
    }

    public static void main(String[] args) {
        Koleksi[] daftar = {
            new Buku("B-001", "Pemrograman Berorientasi Objek"),
            new Majalah("M-014", "Majalah Informatika Edisi September"),
            new Skripsi("S-203", "Sistem Informasi Perpustakaan"),
        };

        int[] hariUji = { 2, 5, 10 };

        System.out.println("=== Biaya Keterlambatan Koleksi ===");
        for (Koleksi k : daftar) {
            System.out.println(k);
            for (int hari : hariUji) {
                System.out.printf("    terlambat %2d hari -> %s%n", hari, rupiah(k.biayaKeterlambatan(hari)));
            }
        }

        double total = 0;
        for (Koleksi k : daftar) total += k.biayaKeterlambatan(10);
        System.out.println();
        System.out.println("Total denda bila ketiganya terlambat 10 hari: " + rupiah(total));

        System.out.println();
        System.out.println("=== Validasi ===");
        try {
            daftar[0].biayaKeterlambatan(-1);
        } catch (IllegalArgumentException e) {
            System.out.println("Ditolak: " + e.getMessage());
        }
    }
}
