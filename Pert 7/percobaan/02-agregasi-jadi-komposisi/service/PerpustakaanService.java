package service;

import model.Anggota;
import model.Koleksi;
import model.Majalah;
import model.Peminjaman;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

/**
 * Lapisan layanan Sistem Perpustakaan.
 *
 * Keputusan relasi (alasan lengkap di analisis.md):
 *  - Koleksi dan Anggota: AGREGASI. Objeknya dibuat petugas di luar layanan lalu
 *    diterima lewat tambahKoleksi/daftarkanAnggota. Koleksi yang dikeluarkan tetap hidup.
 *  - Peminjaman: KOMPOSISI. Hanya pinjam() yang melahirkannya, dan
 *    getPeminjaman() mengembalikan salinan tak-berubah.
 *
 * Penanganan galat memakai exception bawaan; exception khusus menyusul di sesi 10.
 */
public class PerpustakaanService {

    /** Aturan bisnis: satu anggota maksimum tiga koleksi pada saat bersamaan. */
    public static final int BATAS_PINJAMAN_AKTIF = 3;

    private final List<Koleksi> koleksi = new ArrayList<>();         // agregasi
    private final List<Anggota> anggota = new ArrayList<>();         // agregasi
    private final List<Peminjaman> peminjaman = new ArrayList<>();   // komposisi

    // ---- Agregasi: objek bagian DATANG DARI LUAR ----------------------------

    /** KOMPOSISI: layanan sendiri yang membuat Majalah; tidak ada objek Koleksi dari luar. */
    public void tambahMajalah(String nomorInduk, String judul, String penulis, int tahun, int eksemplar) {
        if (cariKoleksi(nomorInduk).isPresent()) {
            throw new IllegalArgumentException("Nomor induk sudah terdaftar: " + nomorInduk);
        }
        koleksi.add(new Majalah(nomorInduk, judul, penulis, tahun, eksemplar));
    }

    /** Tidak lagi mengembalikan objeknya: bagian milik layanan tidak boleh keluar. */
    public void hapusKoleksi(String nomorInduk) {
        Koleksi k = ambilKoleksi(nomorInduk);
        for (Peminjaman p : peminjaman) {
            if (!p.sudahKembali() && p.getKoleksi() == k) {
                throw new IllegalStateException("Koleksi '" + k.getJudul() + "' sedang dipinjam");
            }
        }
        koleksi.remove(k);
    }

    public void daftarkanAnggota(Anggota a) {
        if (a == null) throw new IllegalArgumentException("Anggota tidak boleh null");
        if (cariAnggota(a.getNomorAnggota()).isPresent()) {
            throw new IllegalArgumentException("Nomor anggota sudah terdaftar: " + a.getNomorAnggota());
        }
        anggota.add(a);
    }

    // ---- Pencarian -----------------------------------------------------------

    public Optional<Koleksi> cariKoleksi(String nomorInduk) {
        return koleksi.stream().filter(k -> k.getNomorInduk().equals(nomorInduk)).findFirst();
    }

    public List<Koleksi> cariJudul(String kataKunci) {
        String kunci = kataKunci == null ? "" : kataKunci.toLowerCase();
        return koleksi.stream()
                .filter(k -> k.getJudul().toLowerCase().contains(kunci))
                .toList();
    }

    public Optional<Anggota> cariAnggota(String nomorAnggota) {
        return anggota.stream().filter(a -> a.getNomorAnggota().equals(nomorAnggota)).findFirst();
    }

    // ---- Transaksi -----------------------------------------------------------

    public Peminjaman pinjam(String nomorAnggota, String nomorInduk) {
        return pinjam(nomorAnggota, nomorInduk, LocalDate.now());
    }

    public Peminjaman pinjam(String nomorAnggota, String nomorInduk, LocalDate tanggalPinjam) {
        Anggota a = ambilAnggota(nomorAnggota);
        Koleksi k = ambilKoleksi(nomorInduk);

        // Koleksi yang memutuskan dapat-tidaknya ia dipinjam (datanya ada padanya).
        if (!k.dapatDipinjam()) {
            throw new IllegalStateException(
                    "'" + k.getJudul() + "' tidak dapat dipinjam: " + k.alasanTidakDapatDipinjam());
        }
        // Batas tiga koleksi butuh data seluruh peminjaman, jadi dijaga di layanan.
        if (jumlahPinjamanAktif(a) >= BATAS_PINJAMAN_AKTIF) {
            throw new IllegalStateException(
                    a.getNama() + " sudah meminjam " + BATAS_PINJAMAN_AKTIF + " koleksi");
        }

        k.kurangiStok();
        Peminjaman p = new Peminjaman(a, k, tanggalPinjam);   // komposisi: lahir DI DALAM layanan
        peminjaman.add(p);
        return p;
    }

    /** @return denda dalam rupiah (0 bila tepat waktu) */
    public long kembalikan(String nomorAnggota, String nomorInduk) {
        return kembalikan(nomorAnggota, nomorInduk, LocalDate.now());
    }

    public long kembalikan(String nomorAnggota, String nomorInduk, LocalDate tanggalKembali) {
        Anggota a = ambilAnggota(nomorAnggota);
        Koleksi k = ambilKoleksi(nomorInduk);
        Peminjaman p = peminjaman.stream()
                .filter(x -> !x.sudahKembali() && x.getAnggota() == a && x.getKoleksi() == k)
                .findFirst()
                .orElseThrow(() -> new IllegalStateException(
                        a.getNama() + " tidak sedang meminjam '" + k.getJudul() + "'"));
        p.kembalikan(tanggalKembali);
        k.tambahStok();
        return p.hitungDenda(tanggalKembali);
    }

    // ---- Laporan -------------------------------------------------------------

    /** Daftar koleksi yang sedang dipinjam (kebutuhan petugas). */
    public List<Peminjaman> daftarSedangDipinjam() {
        return peminjaman.stream().filter(p -> !p.sudahKembali()).toList();
    }

    /** Riwayat peminjaman seorang anggota (kebutuhan anggota). */
    public List<Peminjaman> riwayat(String nomorAnggota) {
        Anggota a = ambilAnggota(nomorAnggota);
        return peminjaman.stream().filter(p -> p.getAnggota() == a).toList();
    }

    public List<Koleksi> getKoleksi()        { return List.copyOf(koleksi); }   // masih membocorkan objek Koleksi (lihat catatan)
    public List<Anggota> getAnggota()        { return List.copyOf(anggota); }
    public List<Peminjaman> getPeminjaman()  { return List.copyOf(peminjaman); }

    // ---- Pembantu privat -----------------------------------------------------

    private long jumlahPinjamanAktif(Anggota a) {
        return peminjaman.stream().filter(p -> !p.sudahKembali() && p.getAnggota() == a).count();
    }

    private Koleksi ambilKoleksi(String nomorInduk) {
        return cariKoleksi(nomorInduk).orElseThrow(
                () -> new IllegalArgumentException("Koleksi tidak ditemukan: " + nomorInduk));
    }

    private Anggota ambilAnggota(String nomorAnggota) {
        return cariAnggota(nomorAnggota).orElseThrow(
                () -> new IllegalArgumentException("Anggota tidak ditemukan: " + nomorAnggota));
    }
}
