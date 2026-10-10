/** Buku: dipinjam 14 hari. */
public class Buku extends Koleksi implements Peminjamable {

    private static final int MASA_PINJAM_HARI = 14;

    public Buku(String kode, String judul) { super(kode, judul); }

    @Override public String jenis() { return "BUKU"; }
    @Override public boolean bolehDipinjam() { return true; }
    @Override public int masaPinjamHari() { return MASA_PINJAM_HARI; }
}
