package service;

import model.KelasKuliah;
import model.Mahasiswa;
import model.Pertemuan;
import model.StatusKehadiran;

/**
 * DEPENDENSI: RekapPresensi hanya memakai KelasKuliah sebagai parameter,
 * tidak menyimpannya sebagai bagian dirinya.
 */
public class RekapPresensi {

    public static final double BATAS_LAYAK_UJIAN = 75.0;

    /** Persentase = pertemuan berstatus HADIR / seluruh pertemuan yang sudah dibuka. */
    public double persentaseKehadiran(KelasKuliah kelas, Mahasiswa m) {
        int total = kelas.getPertemuan().size();
        if (total == 0) return 0.0;
        long hadir = kelas.getPertemuan().stream()
                .map(p -> p.statusDari(m))
                .filter(s -> s.isPresent() && s.get().dihitungHadir())
                .count();
        return hadir * 100.0 / total;
    }

    public boolean layakUjian(KelasKuliah kelas, Mahasiswa m) {
        return persentaseKehadiran(kelas, m) >= BATAS_LAYAK_UJIAN;
    }

    public long hitung(KelasKuliah kelas, Mahasiswa m, StatusKehadiran status) {
        return kelas.getPertemuan().stream()
                .map(p -> p.statusDari(m))
                .filter(s -> s.isPresent() && s.get() == status)
                .count();
    }
}
