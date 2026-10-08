/** Majalah: dipinjam 3 hari. */
public class Majalah extends Koleksi implements Peminjamable {

    private static final int MASA_PINJAM_HARI = 3;

    public Majalah(String kode, String judul) { super(kode, judul); }

    @Override public String jenis() { return "MAJALAH"; }
    @Override public boolean bolehDipinjam() { return true; }
    @Override public int masaPinjamHari() { return MASA_PINJAM_HARI; }
}
