package model;

/** Skripsi sama sekali tidak dapat dipinjam; hanya boleh dibaca di tempat. */
public class Skripsi extends Koleksi {

    public Skripsi(String nomorInduk, String judul, String penulis, int tahun, int jumlahEksemplar) {
        super(nomorInduk, judul, penulis, tahun, jumlahEksemplar);
    }

    @Override public int masaPinjamHari()   { return 0; }
    @Override public boolean bolehDipinjam() { return false; }
}
