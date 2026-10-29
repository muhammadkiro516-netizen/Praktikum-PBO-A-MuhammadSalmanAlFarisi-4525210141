/**
 * Satu kelas boleh mewarisi SATU class, tetapi mengimplementasikan BANYAK interface.
 * Alasannya dicatat di keputusan.md.
 */
public class Mobil extends Kendaraan implements Movable, Fuelable {

    private static final double KECEPATAN_MAKSIMUM = 180;

    private final double kapasitasTangki;
    private double isiTangki = 0;

    public Mobil(String merek, int tahun, double kapasitasTangki) {
        super(merek, tahun);
        if (kapasitasTangki <= 0) {
            throw new IllegalArgumentException("Kapasitas tangki harus lebih besar dari 0.");
        }
        this.kapasitasTangki = kapasitasTangki;
    }

    @Override public int jumlahRoda() { return 4; }

    // TODO 1 (selesai): kontrak Movable.
    @Override public void bergerak() {
        System.out.println("  " + merek + " melaju di jalan raya");
    }

    @Override public double kecepatanMaksimum() { return KECEPATAN_MAKSIMUM; }

    // TODO 2 (selesai): kontrak Fuelable.
    // Menolak jumlah <= 0 dan tidak boleh mengisi melebihi kapasitas tangki.
    @Override public void isiBahanBakar(double jumlah) {
        if (jumlah <= 0) {
            throw new IllegalArgumentException("Jumlah bahan bakar harus lebih besar dari 0.");
        }
        if (isiTangki + jumlah > kapasitasTangki) {
            throw new IllegalArgumentException(String.format(
                "Melebihi kapasitas tangki (%.0f dari %.0f liter).", isiTangki + jumlah, kapasitasTangki));
        }
        isiTangki += jumlah;
    }

    @Override public double kapasitasTangki() { return kapasitasTangki; }

    @Override public TipeBahanBakar tipeBahanBakar() { return TipeBahanBakar.BENSIN; }

    public double getIsiTangki() { return isiTangki; }
}
