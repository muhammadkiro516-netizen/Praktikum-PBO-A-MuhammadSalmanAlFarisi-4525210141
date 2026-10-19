/**
 * Pembuktian checkpoint Langkah 1 dan 2: konstruksi yang tidak sah harus ditolak
 * saat objek dibuat, bukan baru ketahuan saat luas() dihitung (NaN).
 * Program terpisah supaya Main.java tidak perlu disunting.
 */
public class UjiValidasi {

    private static void coba(String keterangan, java.util.function.Supplier<BangunDatar> pembuat) {
        try {
            BangunDatar b = pembuat.get();
            System.out.printf("  DITERIMA  %-28s -> luas = %.2f%n", keterangan, b.luas());
        } catch (IllegalArgumentException e) {
            System.out.printf("  DITOLAK   %-28s -> %s%n", keterangan, e.getMessage());
        }
    }

    public static void main(String[] args) {
        System.out.println("=== Uji validasi constructor ===");
        coba("Segitiga(3, 4, 5)",        () -> new Segitiga(3, 4, 5));
        coba("Segitiga(1, 2, 10)",       () -> new Segitiga(1, 2, 10));
        coba("Segitiga(1, 2, 3) (garis)", () -> new Segitiga(1, 2, 3));
        coba("Segitiga(-3, 4, 5)",       () -> new Segitiga(-3, 4, 5));
        coba("Lingkaran(0)",             () -> new Lingkaran(0));
        coba("Lingkaran(-2)",            () -> new Lingkaran(-2));
        coba("Persegi(0)",               () -> new Persegi(0));
        coba("Trapesium(4, 10, 6, 5, 5)", () -> new Trapesium(4, 10, 6, 5, 5));
    }
}
