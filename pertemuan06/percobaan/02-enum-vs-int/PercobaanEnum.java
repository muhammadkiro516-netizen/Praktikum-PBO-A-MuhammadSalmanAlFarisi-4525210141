/**
 * Pertanyaan demo 5: enum TipeBahanBakar vs tiga konstanta int.
 * Bagian A memakai konstanta int: angka sembarang lolos begitu saja.
 * Bagian B memakai enum: nilai yang tidak terdaftar ditolak.
 */
public class PercobaanEnum {

    static final int BENSIN = 1;
    static final int SOLAR = 2;
    static final int LISTRIK = 3;

    static double hargaInt(int tipe) {
        if (tipe == BENSIN)  return 12_000;
        if (tipe == SOLAR)   return 10_500;
        if (tipe == LISTRIK) return 2_500;
        return 0;   // angka tidak dikenal diam-diam menjadi harga 0
    }

    public static void main(String[] args) {
        System.out.println("=== A. Konstanta int ===");
        System.out.println("  hargaInt(1)  = " + hargaInt(1));
        System.out.println("  hargaInt(99) = " + hargaInt(99) + "   <- lolos tanpa peringatan apa pun");

        System.out.println();
        System.out.println("=== B. Enum ===");
        System.out.println("  valueOf(\"SOLAR\")     = " + TipeBahanBakar.valueOf("SOLAR"));
        try {
            TipeBahanBakar.valueOf("HIDROGEN");
        } catch (IllegalArgumentException e) {
            System.out.println("  valueOf(\"HIDROGEN\")  ditolak saat berjalan: " + e.getMessage());
        }
        // Baris berikut ditolak saat KOMPILASI (lihat hasil-kompilasi.txt di bawah):
        // TipeBahanBakar t = 99;
    }
}
