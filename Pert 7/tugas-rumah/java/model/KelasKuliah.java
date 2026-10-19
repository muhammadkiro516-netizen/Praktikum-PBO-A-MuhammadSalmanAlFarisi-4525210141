package model;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

/**
 * Satu kelas perkuliahan.
 *  - Pertemuan: KOMPOSISI (dibuat di dalam lewat bukaPertemuan, maksimum 16)
 *  - Mahasiswa: AGREGASI (peserta diterima dari luar lewat daftarkan)
 *  - MataKuliah dan Dosen: ASOSIASI (hanya dirujuk)
 */
public class KelasKuliah {

    public static final int MAKS_PERTEMUAN = 16;

    private final String kode;
    private final MataKuliah mataKuliah;
    private final Dosen dosen;
    private final List<Pertemuan> pertemuan = new ArrayList<>();   // komposisi
    private final List<Mahasiswa> peserta = new ArrayList<>();     // agregasi

    public KelasKuliah(String kode, MataKuliah mataKuliah, Dosen dosen) {
        if (kode == null || kode.isBlank()) throw new IllegalArgumentException("Kode kelas wajib diisi");
        if (mataKuliah == null || dosen == null) {
            throw new IllegalArgumentException("Mata kuliah dan dosen wajib diisi");
        }
        this.kode = kode;
        this.mataKuliah = mataKuliah;
        this.dosen = dosen;
    }

    // ---- Agregasi ------------------------------------------------------------

    public void daftarkan(Mahasiswa m) {
        if (m == null) throw new IllegalArgumentException("Mahasiswa tidak boleh null");
        if (peserta.contains(m)) throw new IllegalStateException(m.getNama() + " sudah terdaftar");
        peserta.add(m);
    }

    /** Mahasiswa dilepas dari kelas, objeknya tetap hidup. */
    public void keluarkan(Mahasiswa m) { peserta.remove(m); }

    // ---- Komposisi -----------------------------------------------------------

    /** Pertemuan lahir DI DALAM kelas; pemanggil hanya menyerahkan tanggal dan topik. */
    public Pertemuan bukaPertemuan(LocalDate tanggal, String topik) {
        if (pertemuan.size() >= MAKS_PERTEMUAN) {
            throw new IllegalStateException("Satu kelas maksimum " + MAKS_PERTEMUAN + " pertemuan");
        }
        Pertemuan p = new Pertemuan(pertemuan.size() + 1, tanggal, topik);
        pertemuan.add(p);
        return p;
    }

    /** Hanya peserta kelas ini yang boleh dicatat kehadirannya. */
    public void catatKehadiran(int nomorPertemuan, Mahasiswa m, StatusKehadiran status) {
        if (!peserta.contains(m)) {
            throw new IllegalStateException("Mahasiswa bukan peserta kelas " + kode);
        }
        if (nomorPertemuan < 1 || nomorPertemuan > pertemuan.size()) {
            throw new IllegalArgumentException("Pertemuan " + nomorPertemuan + " belum dibuka");
        }
        pertemuan.get(nomorPertemuan - 1).catat(m, status);
    }

    public List<Pertemuan> getPertemuan() { return List.copyOf(pertemuan); }
    public List<Mahasiswa> getPeserta()   { return List.copyOf(peserta); }
    public String getKode()               { return kode; }
    public MataKuliah getMataKuliah()     { return mataKuliah; }
    public Dosen getDosen()               { return dosen; }

    @Override
    public String toString() {
        return String.format("%s - %s | pengampu %s | %d peserta, %d pertemuan",
                kode, mataKuliah.getNama(), dosen.getNama(), peserta.size(), pertemuan.size());
    }
}
