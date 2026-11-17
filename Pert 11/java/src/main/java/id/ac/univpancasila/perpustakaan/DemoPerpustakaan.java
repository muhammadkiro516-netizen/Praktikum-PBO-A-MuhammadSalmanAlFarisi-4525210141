package id.ac.univpancasila.perpustakaan;

import id.ac.univpancasila.perpustakaan.model.Anggota;
import id.ac.univpancasila.perpustakaan.model.Buku;
import id.ac.univpancasila.perpustakaan.model.KeanggotaanDosen;
import id.ac.univpancasila.perpustakaan.model.KeanggotaanKehormatan;
import id.ac.univpancasila.perpustakaan.model.KeanggotaanMahasiswa;
import id.ac.univpancasila.perpustakaan.model.Koleksi;
import id.ac.univpancasila.perpustakaan.model.Majalah;
import id.ac.univpancasila.perpustakaan.model.Peminjaman;
import id.ac.univpancasila.perpustakaan.model.Skripsi;
import id.ac.univpancasila.perpustakaan.service.PerpustakaanService;
import id.ac.univpancasila.perpustakaan.util.Format;

import org.apache.commons.lang3.StringUtils;   // dependensi eksternal (Apache Commons Lang)

import java.time.LocalDate;

/**
 * Titik masuk. Jalankan dengan:
 *     mvn exec:java -Dexec.mainClass=id.ac.univpancasila.perpustakaan.DemoPerpustakaan
 */
public class DemoPerpustakaan {

    public static void main(String[] args) {
        PerpustakaanService layanan = new PerpustakaanService();

        Buku pbo = new Buku("PBO-001", "Pemrograman Berorientasi Objek", "A. W. Pribadi", 2020, 3);
        pbo.tambahBab(1, "Paradigma Objek");
        pbo.tambahBab(2, "Enkapsulasi");
        pbo.tambahBab(3, "Pewarisan dan Polimorfisme");
        Majalah majalah = new Majalah("MJ-001", "Majalah Teknologi", "Redaksi", 2026, 1);
        layanan.tambahKoleksi(pbo);
        layanan.tambahKoleksi(new Buku("BD-001", "Sistem Basis Data", "R. Elmasri", 2016, 2));
        layanan.tambahKoleksi(majalah);
        layanan.tambahKoleksi(new Skripsi("SK-001", "Analisis Sistem Informasi", "Mahasiswa TI", 2025, 1));

        layanan.daftarkanAnggota(new Anggota("A001", "Ani Lestari", new KeanggotaanMahasiswa()));
        layanan.daftarkanAnggota(new Anggota("D001", "Dr. Rina Pratama", new KeanggotaanDosen()));
        layanan.daftarkanAnggota(new Anggota("K001", "Pak Hasan", new KeanggotaanKehormatan()));

        System.out.println("=== Daftar koleksi ===");
        cetakTabel(layanan);

        LocalDate awal = LocalDate.of(2026, 10, 1);
        System.out.println();
        System.out.println("=== Peminjaman (" + Format.tanggal(awal) + ") ===");
        cetakPinjam(layanan.pinjam("A001", "PBO-001", awal));
        cetakPinjam(layanan.pinjam("A001", "MJ-001", awal));
        cetakPinjam(layanan.pinjam("D001", "BD-001", awal));
        layanan.pinjam("A001", "BD-001", awal);        // pinjaman ke-3 Ani

        System.out.println();
        System.out.println("=== Aturan yang harus menolak ===");
        coba("Pinjam skripsi",          () -> layanan.pinjam("A001", "SK-001", awal));
        coba("Majalah sudah habis",     () -> layanan.pinjam("K001", "MJ-001", awal));
        coba("Pinjaman ke-4 Ani",       () -> layanan.pinjam("A001", "PBO-001", awal));

        LocalDate kembali = LocalDate.of(2026, 10, 20);
        System.out.println();
        System.out.println("=== Pengembalian terlambat (" + Format.tanggal(kembali) + ") ===");
        cetakKembali(layanan.kembalikan("A001", "PBO-001", kembali));
        cetakKembali(layanan.kembalikan("D001", "BD-001", kembali));
        layanan.pinjam("K001", "PBO-001", awal);
        cetakKembali(layanan.kembalikan("K001", "PBO-001", kembali));

        System.out.println();
        System.out.println("=== Daftar koleksi setelah transaksi ===");
        cetakTabel(layanan);

        System.out.println();
        System.out.println("=== Sedang dipinjam ===");
        for (Peminjaman p : layanan.daftarSedangDipinjam()) {
            System.out.println("  " + p.getAnggota().getNama() + " -> " + p.getKoleksi().getJudul()
                    + " (jatuh tempo " + Format.tanggal(p.getTanggalJatuhTempo()) + ")");
        }
    }

    /** Tabel rapi memakai StringUtils dari Apache Commons Lang. */
    private static void cetakTabel(PerpustakaanService layanan) {
        System.out.println(StringUtils.rightPad("Nomor", 9) + StringUtils.rightPad("Judul", 33)
                + StringUtils.rightPad("Jenis", 9) + StringUtils.leftPad("Eks", 3) + "  Status");
        System.out.println(StringUtils.repeat('-', 66));
        for (Koleksi k : layanan.daftarKoleksi()) {
            System.out.println(StringUtils.rightPad(k.getNomorInduk(), 9)
                    + StringUtils.rightPad(k.getJudul(), 33)
                    + StringUtils.rightPad(k.getClass().getSimpleName(), 9)
                    + StringUtils.leftPad(String.valueOf(k.getJumlahEksemplar()), 3)
                    + "  " + k.getStatus().getLabel());
        }
    }

    private static void cetakPinjam(Peminjaman p) {
        System.out.println("  " + p.getAnggota().getNama() + " meminjam " + p.getKoleksi().getJudul()
                + ", jatuh tempo " + Format.tanggal(p.getTanggalJatuhTempo()));
    }

    private static void cetakKembali(Peminjaman p) {
        System.out.println("  " + p.getAnggota().getNama() + " (" + p.getAnggota().getJenis().nama() + ") "
                + "mengembalikan " + p.getKoleksi().getJudul() + ": terlambat " + p.hariTerlambat()
                + " hari, denda " + Format.rupiah(p.hitungDenda()));
    }

    private static void coba(String keterangan, Runnable aksi) {
        try {
            aksi.run();
            System.out.println("  MASALAH: \"" + keterangan + "\" seharusnya ditolak.");
        } catch (IllegalStateException | IllegalArgumentException e) {
            System.out.println("  Ditolak (" + keterangan + "): " + e.getMessage());
        }
    }
}
