package model;

import java.time.LocalDate;
import java.time.temporal.ChronoUnit;
import java.util.Optional;

/**
 * Satu transaksi: satu anggota, satu koleksi. Anggota dan Koleksi hanya
 * DIRUJUK (asosiasi); keduanya tetap hidup tanpa Peminjaman ini.
 */
public class Peminjaman {

    private final Anggota anggota;
    private final Koleksi koleksi;
    private final LocalDate tanggalPinjam;
    private final LocalDate tanggalJatuhTempo;
    private LocalDate tanggalKembali;   // null selama belum dikembalikan

    public Peminjaman(Anggota anggota, Koleksi koleksi, LocalDate tanggalPinjam) {
        if (anggota == null || koleksi == null || tanggalPinjam == null) {
            throw new IllegalArgumentException("Anggota, koleksi, dan tanggal pinjam wajib diisi");
        }
        this.anggota = anggota;
        this.koleksi = koleksi;
        this.tanggalPinjam = tanggalPinjam;
        // Jatuh tempo = tanggal pinjam + masa pinjam koleksi yang bersangkutan.
        this.tanggalJatuhTempo = tanggalPinjam.plusDays(koleksi.masaPinjamHari());
    }

    public boolean sudahKembali() { return tanggalKembali != null; }

    public void kembalikan(LocalDate tanggal) {
        if (sudahKembali()) {
            throw new IllegalStateException("Peminjaman ini sudah dikembalikan pada " + tanggalKembali);
        }
        if (tanggal == null || tanggal.isBefore(tanggalPinjam)) {
            throw new IllegalArgumentException("Tanggal kembali tidak boleh sebelum tanggal pinjam");
        }
        this.tanggalKembali = tanggal;
    }

    /** Hari terlambat dihitung sampai tanggal acuan (hari ini bila belum kembali). Tidak pernah negatif. */
    public long hariTerlambat(LocalDate tanggalAcuan) {
        LocalDate akhir = sudahKembali() ? tanggalKembali : tanggalAcuan;
        return Math.max(0, ChronoUnit.DAYS.between(tanggalJatuhTempo, akhir));
    }

    /** Denda = hari terlambat x denda per hari milik jenis keanggotaan (dependensi ke JenisKeanggotaan). */
    public long hitungDenda(LocalDate tanggalAcuan) {
        return hariTerlambat(tanggalAcuan) * anggota.getJenis().dendaPerHari();
    }

    public Anggota getAnggota()                   { return anggota; }
    public Koleksi getKoleksi()                   { return koleksi; }
    public LocalDate getTanggalPinjam()           { return tanggalPinjam; }
    public LocalDate getTanggalJatuhTempo()       { return tanggalJatuhTempo; }
    public Optional<LocalDate> getTanggalKembali() { return Optional.ofNullable(tanggalKembali); }

    @Override
    public String toString() {
        return String.format("%s meminjam '%s' | pinjam %s, tempo %s, kembali %s",
                anggota.getNama(), koleksi.getJudul(), tanggalPinjam, tanggalJatuhTempo,
                sudahKembali() ? tanggalKembali : "-");
    }
}
