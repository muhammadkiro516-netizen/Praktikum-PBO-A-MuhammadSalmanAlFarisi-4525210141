package model;

import java.util.ArrayList;
import java.util.List;

/**
 * Sesi 7 - KOMPOSISI: Buku memiliki 1..* Bab.
 *
 * Tiga hal yang membuat relasi ini benar-benar komposisi:
 *   1. tidak ada constructor atau method yang MENERIMA objek Bab dari luar
 *   2. Bab dibuat DI DALAM Buku (constructor Bab privat)
 *   3. getBab() mengembalikan salinan yang tidak bisa diubah
 * Multiplicity 1..* dijaga oleh constructor: daftar judul bab tidak boleh kosong.
 */
public class Buku extends Koleksi {

    private static final int MASA_PINJAM_HARI = 14;

    private final List<Bab> bab = new ArrayList<>();

    /**
     * @param judulBab judul bab-bab awal (bukan objek Bab). Minimal satu,
     *                 diberi nomor 1, 2, 3, ... sesuai urutan.
     */
    public Buku(String nomorInduk, String judul, String penulis, int tahun,
                int jumlahEksemplar, List<String> judulBab) {
        super(nomorInduk, judul, penulis, tahun, jumlahEksemplar);
        // Menjamin multiplicity 1..* pada diagram: buku tanpa bab tidak pernah lahir.
        if (judulBab == null || judulBab.isEmpty()) {
            throw new IllegalArgumentException("Buku harus memiliki minimal satu bab");
        }
        for (String j : judulBab) {
            buatBab(bab.size() + 1, j);
        }
    }

    /**
     * Satu-satunya cara Bab lahir. Parameternya nomor dan judul, BUKAN objek Bab.
     * Kalau menerima objek Bab dari luar, relasi ini berubah menjadi agregasi.
     */
    public void tambahBab(int nomor, String judulBab) {
        buatBab(nomor, judulBab);
    }

    /** Dipisah sebagai method privat agar constructor tidak memanggil method yang bisa ditimpa. */
    private void buatBab(int nomor, String judulBab) {
        if (nomor <= 0) {
            throw new IllegalArgumentException("Nomor bab harus positif: " + nomor);
        }
        if (judulBab == null || judulBab.isBlank()) {
            throw new IllegalArgumentException("Judul bab tidak boleh kosong");
        }
        for (Bab b : bab) {
            if (b.getNomor() == nomor) {
                throw new IllegalArgumentException("Bab nomor " + nomor + " sudah ada");
            }
        }
        bab.add(new Bab(nomor, judulBab));
    }

    /** Salinan tak-berubah: pemanggil tidak bisa menambah/menghapus bab dari luar. */
    public List<Bab> getBab() {
        return List.copyOf(bab);
    }

    @Override public int masaPinjamHari()   { return MASA_PINJAM_HARI; }
    @Override public boolean bolehDipinjam() { return true; }

    @Override
    public String toString() {
        return super.toString() + " {" + bab.size() + " bab}";
    }

    /**
     * Bab adalah BAGIAN dari Buku. Kelasnya bersarang untuk menegaskan itu,
     * dan constructor-nya privat agar hanya Buku yang boleh membuatnya.
     */
    public static final class Bab {
        private final int nomor;
        private final String judul;

        private Bab(int nomor, String judul) {
            this.nomor = nomor;
            this.judul = judul;
        }

        public int getNomor()    { return nomor; }
        public String getJudul() { return judul; }

        @Override public String toString() { return "Bab " + nomor + ": " + judul; }
    }
}
