/**
 * Jawaban pertanyaan demo nomor 3: butuh bahan bakar tetapi tidak bergerak.
 * Genset hanya mengimplementasikan Fuelable. Ia bahkan tidak perlu menjadi
 * Kendaraan, sebab genset memang bukan kendaraan (identitasnya berbeda).
 * isiPenuh(Fuelable) di Main langsung menerimanya tanpa menyunting satu baris pun.
 */
public class Genset implements Fuelable {

    private final String nama;
    private final double kapasitasTangki;
    private double isiTangki = 0;

    public Genset(String nama, double kapasitasTangki) {
        if (nama == null || nama.isBlank()) {
            throw new IllegalArgumentException("Nama genset tidak boleh kosong.");
        }
        if (kapasitasTangki <= 0) {
            throw new IllegalArgumentException("Kapasitas tangki harus lebih besar dari 0.");
        }
        this.nama = nama;
        this.kapasitasTangki = kapasitasTangki;
    }

    @Override public void isiBahanBakar(double jumlah) {
        if (jumlah <= 0) {
            throw new IllegalArgumentException("Jumlah bahan bakar harus lebih besar dari 0.");
        }
        if (isiTangki + jumlah > kapasitasTangki) {
            throw new IllegalArgumentException("Melebihi kapasitas tangki genset.");
        }
        isiTangki += jumlah;
    }

    @Override public double kapasitasTangki() { return kapasitasTangki; }

    @Override public TipeBahanBakar tipeBahanBakar() { return TipeBahanBakar.SOLAR; }

    public double getIsiTangki() { return isiTangki; }

    @Override public String toString() { return nama + " (genset, tidak bergerak)"; }
}
