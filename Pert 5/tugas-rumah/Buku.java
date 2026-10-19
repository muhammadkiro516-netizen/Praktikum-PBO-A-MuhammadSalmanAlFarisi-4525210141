/** Buku: denda tetap Rp1.000 per hari. */
public class Buku extends Koleksi {

    private static final double DENDA_PER_HARI = 1_000;

    public Buku(String kode, String judul) {
        super(kode, judul);
    }

    @Override
    public double biayaKeterlambatan(int hariTerlambat) {
        return pastikanHariSah(hariTerlambat) * DENDA_PER_HARI;
    }

    @Override
    public String jenis() { return "BUKU"; }
}
