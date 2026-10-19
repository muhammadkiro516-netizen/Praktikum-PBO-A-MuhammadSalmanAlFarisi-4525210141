package model;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

/**
 * KOMPOSISI: Pertemuan memiliki 0..* Presensi.
 * Presensi bersarang dengan constructor privat: hanya Pertemuan yang bisa melahirkannya,
 * dan getPresensi() mengembalikan salinan tak-berubah.
 *
 * Pertemuan sendiri adalah bagian dari KelasKuliah; constructor-nya package-private
 * sehingga hanya KelasKuliah.bukaPertemuan() yang membuatnya.
 */
public class Pertemuan {

    private final int nomor;
    private final LocalDate tanggal;
    private final String topik;
    private final List<Presensi> presensi = new ArrayList<>();

    Pertemuan(int nomor, LocalDate tanggal, String topik) {
        if (nomor <= 0) throw new IllegalArgumentException("Nomor pertemuan harus positif: " + nomor);
        if (tanggal == null) throw new IllegalArgumentException("Tanggal wajib diisi");
        if (topik == null || topik.isBlank()) throw new IllegalArgumentException("Topik wajib diisi");
        this.nomor = nomor;
        this.tanggal = tanggal;
        this.topik = topik;
    }

    /** Satu-satunya cara Presensi lahir. Satu mahasiswa hanya satu catatan per pertemuan. */
    void catat(Mahasiswa mahasiswa, StatusKehadiran status) {
        if (mahasiswa == null || status == null) {
            throw new IllegalArgumentException("Mahasiswa dan status wajib diisi");
        }
        if (statusDari(mahasiswa).isPresent()) {
            throw new IllegalStateException(mahasiswa.getNama() + " sudah dicatat pada pertemuan " + nomor);
        }
        presensi.add(new Presensi(mahasiswa, status));
    }

    public Optional<StatusKehadiran> statusDari(Mahasiswa mahasiswa) {
        return presensi.stream()
                .filter(p -> p.getMahasiswa() == mahasiswa)
                .map(Presensi::getStatus)
                .findFirst();
    }

    public List<Presensi> getPresensi() { return List.copyOf(presensi); }
    public int getNomor()               { return nomor; }
    public LocalDate getTanggal()       { return tanggal; }
    public String getTopik()            { return topik; }

    @Override
    public String toString() {
        return "Pertemuan " + nomor + " (" + tanggal + "): " + topik + " [" + presensi.size() + " tercatat]";
    }

    public static final class Presensi {
        private final Mahasiswa mahasiswa;
        private final StatusKehadiran status;

        private Presensi(Mahasiswa mahasiswa, StatusKehadiran status) {
            this.mahasiswa = mahasiswa;
            this.status = status;
        }

        public Mahasiswa getMahasiswa()   { return mahasiswa; }
        public StatusKehadiran getStatus() { return status; }

        @Override public String toString() { return mahasiswa.getNama() + ": " + status.getLabel(); }
    }
}
