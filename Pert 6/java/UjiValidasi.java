/**
 * Pembuktian bahwa masukan yang tidak sah ditolak. Dibuat terpisah supaya
 * Main.java tidak perlu disunting.
 */
public class UjiValidasi {

    interface Percobaan { void jalankan(); }

    static void coba(String nama, Percobaan p) {
        try {
            p.jalankan();
            System.out.printf("  DITERIMA  %-34s%n", nama);
        } catch (IllegalArgumentException e) {
            System.out.printf("  DITOLAK   %-34s -> %s%n", nama, e.getMessage());
        }
    }

    public static void main(String[] args) {
        System.out.println("=== Uji validasi ===");
        coba("Mobil.isiBahanBakar(20) pada 45 L", () -> new Mobil("Avanza", 2022, 45).isiBahanBakar(20));
        coba("Mobil.isiBahanBakar(0)",            () -> new Mobil("Avanza", 2022, 45).isiBahanBakar(0));
        coba("Mobil.isiBahanBakar(-5)",           () -> new Mobil("Avanza", 2022, 45).isiBahanBakar(-5));
        coba("Isi 30 lalu 30 pada tangki 45 L",   () -> {
            Mobil m = new Mobil("Avanza", 2022, 45);
            m.isiBahanBakar(30);
            m.isiBahanBakar(30);
        });
        coba("Mobil dengan merek kosong",         () -> new Mobil(" ", 2022, 45));
        coba("Sepeda tahun 0",                    () -> new Sepeda("Polygon", 0));
        coba("Mobil kapasitas tangki 0",          () -> new Mobil("Avanza", 2022, 0));
        coba("biayaPengisian(-1)",                () -> TipeBahanBakar.BENSIN.biayaPengisian(-1));

        System.out.println();
        System.out.println("=== umur() tidak pernah negatif ===");
        Kendaraan k = new Mobil("Avanza", 2022, 45);
        System.out.println("  umur pada 2026 = " + k.umur(2026));
        System.out.println("  umur pada 2020 = " + k.umur(2020) + "  (tahun sekarang lebih kecil dari tahun pembuatan)");
    }
}
