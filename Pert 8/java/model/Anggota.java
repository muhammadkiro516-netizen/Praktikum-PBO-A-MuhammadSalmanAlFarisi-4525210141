package model;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

/**
 * Anggota perpustakaan. Nomor anggota tidak pernah berubah setelah didaftarkan,
 * dan seorang anggota tidak boleh meminjam lebih dari tiga koleksi sekaligus.
 *
 * Anggota ke Peminjaman adalah KOMPOSISI: Peminjaman hanya dibuat di dalam
 * Anggota (constructor-nya tidak publik) dan tidak punya arti tanpa anggotanya.
 */
public class Anggota {

    public static final int BATAS_PINJAMAN = 3;

    private final String nomorAnggota;
    private final String nama;
    private final JenisKeanggotaan jenis;
    private final List<Peminjaman> riwayat = new ArrayList<>();

    public Anggota(String nomorAnggota, String nama, JenisKeanggotaan jenis) {
        if (nomorAnggota == null || nomorAnggota.isBlank())
            throw new IllegalArgumentException("Nomor anggota tidak boleh kosong.");
        if (nama == null || nama.isBlank())
            throw new IllegalArgumentException("Nama anggota tidak boleh kosong.");
        if (jenis == null)
            throw new IllegalArgumentException("Jenis keanggotaan harus diisi.");
        this.nomorAnggota = nomorAnggota;
        this.nama = nama;
        this.jenis = jenis;
    }

    public int jumlahPinjamanAktif() {
        int n = 0;
        for (Peminjaman p : riwayat) {
            if (!p.sudahKembali()) n++;
        }
        return n;
    }

    public boolean bolehMeminjamLagi() {
        return jumlahPinjamanAktif() < BATAS_PINJAMAN;
    }

    /** Satu-satunya cara Peminjaman lahir. */
    public Peminjaman catatPeminjaman(Koleksi koleksi, LocalDate tanggalPinjam) {
        if (koleksi == null || tanggalPinjam == null)
            throw new IllegalArgumentException("Koleksi dan tanggal pinjam harus diisi.");
        if (!bolehMeminjamLagi())
            throw new IllegalStateException(nama + " sudah meminjam " + BATAS_PINJAMAN
                    + " koleksi (batas maksimum).");
        Peminjaman p = new Peminjaman(this, koleksi, tanggalPinjam);
        riwayat.add(p);
        return p;
    }

    /** Salinan tak-berubah, sehingga riwayat tidak bisa diubah dari luar. */
    public List<Peminjaman> getRiwayat() {
        return List.copyOf(riwayat);
    }

    public String getNomorAnggota()    { return nomorAnggota; }
    public String getNama()            { return nama; }
    public JenisKeanggotaan getJenis() { return jenis; }

    @Override
    public String toString() {
        return nomorAnggota + " " + nama + " (" + jenis.nama() + ")";
    }
}
