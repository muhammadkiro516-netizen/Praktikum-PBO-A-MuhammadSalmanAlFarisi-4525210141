/** Skripsi: koleksi langka, hanya dibaca di tempat, tidak boleh dipinjam. */
public class Skripsi extends Koleksi implements Peminjamable {

    public Skripsi(String kode, String judul) { super(kode, judul); }

    @Override public String jenis() { return "SKRIPSI"; }
    @Override public boolean bolehDipinjam() { return false; }
    @Override public int masaPinjamHari() { return 0; }
}
