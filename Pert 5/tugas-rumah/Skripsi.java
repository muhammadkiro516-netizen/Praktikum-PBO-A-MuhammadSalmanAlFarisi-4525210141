/**
 * Skripsi: koleksi langka (hanya satu eksemplar).
 * Rp5.000 per hari, dan Rp50.000 per hari setelah lewat 7 hari.
 * Tarif naik karena eksemplarnya tidak tergantikan dan banyak mahasiswa menunggu.
 */
public class Skripsi extends Koleksi {

    private static final double DENDA_PER_HARI_NORMAL = 5_000;
    private static final double DENDA_PER_HARI_LEWAT_BATAS = 50_000;
    private static final int    BATAS_HARI_NORMAL = 7;

    public Skripsi(String kode, String judul) {
        super(kode, judul);
    }

    @Override
    public double biayaKeterlambatan(int hariTerlambat) {
        int hari = pastikanHariSah(hariTerlambat);
        int hariNormal = Math.min(hari, BATAS_HARI_NORMAL);
        int hariLewat  = hari - hariNormal;
        return hariNormal * DENDA_PER_HARI_NORMAL + hariLewat * DENDA_PER_HARI_LEWAT_BATAS;
    }

    @Override
    public String jenis() { return "SKRIPSI"; }
}
