package service;

import model.Anggota;
import model.Koleksi;
import model.Peminjaman;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

/**
 * Lapisan layanan perpustakaan.
 *
 * Keputusan relasi: layanan ini MENGAGREGASI koleksi dan anggota, bukan
 * mengkomposisinya. Objek Koleksi dan Anggota dibuat di luar lalu diterima
 * lewat tambahKoleksi() dan daftarkanAnggota(), dan keduanya tetap bermakna
 * bila layanan diganti atau dibubarkan. Alasan lengkap ada di analisis.md.
 *
 * Aturan bisnis di sini masih dasar; penanganan galat akan disempurnakan
 * pada sesi 10 (exception).
 */
public class PerpustakaanService {

    private final Map<String, Koleksi> koleksi = new LinkedHashMap<>();
    private final Map<String, Anggota> anggota = new LinkedHashMap<>();

    public void tambahKoleksi(Koleksi k) {
        if (k == null)
            throw new IllegalArgumentException("Koleksi tidak boleh null.");
        if (koleksi.containsKey(k.getNomorInduk()))
            throw new IllegalArgumentException("Nomor induk " + k.getNomorInduk() + " sudah terdaftar.");
        koleksi.put(k.getNomorInduk(), k);
    }

    public void daftarkanAnggota(Anggota a) {
        if (a == null)
            throw new IllegalArgumentException("Anggota tidak boleh null.");
        if (anggota.containsKey(a.getNomorAnggota()))
            throw new IllegalArgumentException("Nomor anggota " + a.getNomorAnggota() + " sudah terdaftar.");
        anggota.put(a.getNomorAnggota(), a);
    }

    /** Mengembalikan null bila nomor induk tidak ditemukan. */
    public Koleksi cariKoleksi(String nomorInduk) {
        return koleksi.get(nomorInduk);
    }

    public Peminjaman pinjam(String nomorAnggota, String nomorInduk) {
        return pinjam(nomorAnggota, nomorInduk, LocalDate.now());
    }

    public Peminjaman pinjam(String nomorAnggota, String nomorInduk, LocalDate tanggal) {
        Anggota a = ambilAnggota(nomorAnggota);
        Koleksi k = ambilKoleksi(nomorInduk);

        // Semua pemeriksaan dilakukan sebelum ada data yang berubah.
        if (!k.bolehDipinjam())
            throw new IllegalStateException(k.getJudul() + " tidak dapat dipinjam, hanya boleh dibaca di tempat.");
        if (!k.tersedia())
            throw new IllegalStateException(k.getJudul() + " tidak tersedia (status "
                    + k.getStatus().getLabel() + ", " + k.getJumlahEksemplar() + " eksemplar).");
        if (!a.bolehMeminjamLagi())
            throw new IllegalStateException(a.getNama() + " sudah meminjam " + Anggota.BATAS_PINJAMAN
                    + " koleksi (batas maksimum).");

        Peminjaman p = a.catatPeminjaman(k, tanggal);
        k.kurangiStok();
        return p;
    }

    public Peminjaman kembalikan(String nomorAnggota, String nomorInduk) {
        return kembalikan(nomorAnggota, nomorInduk, LocalDate.now());
    }

    public Peminjaman kembalikan(String nomorAnggota, String nomorInduk, LocalDate tanggal) {
        Anggota a = ambilAnggota(nomorAnggota);
        Koleksi k = ambilKoleksi(nomorInduk);
        for (Peminjaman p : a.getRiwayat()) {
            if (!p.sudahKembali() && p.getKoleksi() == k) {
                p.kembalikan(tanggal);
                k.tambahStok();
                return p;
            }
        }
        throw new IllegalStateException(a.getNama() + " tidak sedang meminjam " + k.getJudul() + ".");
    }

    /** Untuk petugas: semua peminjaman yang belum dikembalikan. */
    public List<Peminjaman> daftarSedangDipinjam() {
        List<Peminjaman> hasil = new ArrayList<>();
        for (Anggota a : anggota.values()) {
            for (Peminjaman p : a.getRiwayat()) {
                if (!p.sudahKembali()) hasil.add(p);
            }
        }
        return hasil;
    }

    /** Untuk anggota: riwayat peminjamannya sendiri. */
    public List<Peminjaman> riwayatAnggota(String nomorAnggota) {
        return ambilAnggota(nomorAnggota).getRiwayat();
    }

    private Anggota ambilAnggota(String nomorAnggota) {
        Anggota a = anggota.get(nomorAnggota);
        if (a == null) throw new IllegalArgumentException("Anggota " + nomorAnggota + " tidak ditemukan.");
        return a;
    }

    private Koleksi ambilKoleksi(String nomorInduk) {
        Koleksi k = koleksi.get(nomorInduk);
        if (k == null) throw new IllegalArgumentException("Koleksi " + nomorInduk + " tidak ditemukan.");
        return k;
    }
}
