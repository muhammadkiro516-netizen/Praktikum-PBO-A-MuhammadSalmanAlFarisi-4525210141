package model;

import java.util.ArrayList;
import java.util.List;

/**
 * Sesi 9 — KOMPOSISI.
 *
 * Tiga hal yang membuat sebuah relasi benar-benar komposisi:
 *   1. tidak ada constructor atau method yang MENERIMA objek bagian dari luar
 *   2. objek bagian dibuat DI DALAM induk
 *   3. getter mengembalikan salinan yang tidak bisa diubah
 */
public class Buku extends Koleksi {

    private static final int MASA_PINJAM_HARI = 14;

    private final List<Bab> bab = new ArrayList<>();

    public Buku(String nomorInduk, String judul, String penulis, int tahun, int jumlahEksemplar) {
        // Validasi jumlah eksemplar negatif dilakukan di constructor Koleksi.
        super(nomorInduk, judul, penulis, tahun, jumlahEksemplar);
    }

    /**
     * Satu-satunya cara Bab lahir.
     * Parameternya nomor dan judul, BUKAN objek Bab: Bab dibuat di dalam Buku.
     */
    public void tambahBab(int nomor, String judulBab) {
        if (nomor <= 0)
            throw new IllegalArgumentException("Nomor bab harus lebih besar dari 0.");
        if (judulBab == null || judulBab.isBlank())
            throw new IllegalArgumentException("Judul bab tidak boleh kosong.");
        for (Bab b : bab) {
            if (b.getNomor() == nomor)
                throw new IllegalArgumentException("Bab nomor " + nomor + " sudah ada.");
        }
        bab.add(new Bab(nomor, judulBab));
    }

    /** Mengembalikan salinan yang tidak bisa diubah, sehingga daftar asli tetap aman. */
    public List<Bab> getBab() {
        return List.copyOf(bab);
    }

    @Override public int masaPinjamHari()    { return MASA_PINJAM_HARI; }
    @Override public boolean bolehDipinjam() { return true; }

    @Override
    public String toString() {
        return String.format("%-32s %-16s [%d eks, %d bab]",
                getJudul(), getPenulis(), getJumlahEksemplar(), bab.size());
    }

    /**
     * Bab adalah BAGIAN dari Buku — kelasnya bersarang untuk menegaskan itu,
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
