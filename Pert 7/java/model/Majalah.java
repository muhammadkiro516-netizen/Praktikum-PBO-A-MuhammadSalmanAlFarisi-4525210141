package model;

/** Majalah hanya dapat dipinjam selama 3 hari. */
public class Majalah extends Koleksi {

    private static final int MASA_PINJAM_HARI = 3;

    public Majalah(String nomorInduk, String judul, String penulis, int tahun, int jumlahEksemplar) {
        super(nomorInduk, judul, penulis, tahun, jumlahEksemplar);
    }

    @Override public int masaPinjamHari()   { return MASA_PINJAM_HARI; }
    @Override public boolean bolehDipinjam() { return true; }
}
