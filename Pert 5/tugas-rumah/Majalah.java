/**
 * Majalah: Rp500 per hari, tetapi 3 hari pertama bebas denda (masa tenggang).
 * Majalah edisi lama mudah dicari lagi, jadi dendanya lebih ringan.
 */
public class Majalah extends Koleksi {

    private static final double DENDA_PER_HARI = 500;
    private static final int    HARI_TENGGANG  = 3;

    public Majalah(String kode, String judul) {
        super(kode, judul);
    }

    @Override
    public double biayaKeterlambatan(int hariTerlambat) {
        int hariKena = Math.max(0, pastikanHariSah(hariTerlambat) - HARI_TENGGANG);
        return hariKena * DENDA_PER_HARI;
    }

    @Override
    public String jenis() { return "MAJALAH"; }
}
