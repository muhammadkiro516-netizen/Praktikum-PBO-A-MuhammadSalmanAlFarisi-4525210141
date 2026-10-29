package model;

import java.time.LocalDate;
import java.time.temporal.ChronoUnit;

/**
 * Satu catatan peminjaman: satu anggota, satu koleksi, tanggal pinjam,
 * tanggal jatuh tempo, dan tanggal kembali (kosong selama belum dikembalikan).
 * Jatuh tempo dihitung dari tanggal pinjam ditambah masa pinjam koleksi.
 */
public class Peminjaman {

    private final Anggota anggota;
    private final Koleksi koleksi;
    private final LocalDate tanggalPinjam;
    private final LocalDate tanggalJatuhTempo;
    private LocalDate tanggalKembali;   // null selama belum dikembalikan

    /** Tidak publik: hanya Anggota (satu paket) yang boleh membuat Peminjaman. */
    Peminjaman(Anggota anggota, Koleksi koleksi, LocalDate tanggalPinjam) {
        this.anggota = anggota;
        this.koleksi = koleksi;
        this.tanggalPinjam = tanggalPinjam;
        this.tanggalJatuhTempo = tanggalPinjam.plusDays(koleksi.masaPinjamHari());
    }

    public boolean sudahKembali() {
        return tanggalKembali != null;
    }

    public void kembalikan(LocalDate tanggal) {
        if (sudahKembali())
            throw new IllegalStateException("Peminjaman ini sudah dikembalikan.");
        if (tanggal == null || tanggal.isBefore(tanggalPinjam))
            throw new IllegalArgumentException("Tanggal kembali tidak boleh sebelum tanggal pinjam.");
        this.tanggalKembali = tanggal;
    }

    /** Jumlah hari terlambat; 0 bila tepat waktu atau belum dikembalikan. */
    public long hariTerlambat() {
        if (!sudahKembali()) return 0;
        return Math.max(0, ChronoUnit.DAYS.between(tanggalJatuhTempo, tanggalKembali));
    }

    /** Denda = hari terlambat x denda per hari menurut jenis keanggotaan. */
    public long hitungDenda() {
        return hariTerlambat() * anggota.getJenis().dendaPerHari();
    }

    public Anggota getAnggota()             { return anggota; }
    public Koleksi getKoleksi()             { return koleksi; }
    public LocalDate getTanggalPinjam()     { return tanggalPinjam; }
    public LocalDate getTanggalJatuhTempo() { return tanggalJatuhTempo; }
    public LocalDate getTanggalKembali()    { return tanggalKembali; }
}
