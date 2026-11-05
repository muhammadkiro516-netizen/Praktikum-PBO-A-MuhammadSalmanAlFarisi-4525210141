import model.Anggota;
import model.Buku;
import model.Jurusan;
import model.KeanggotaanDosen;
import model.KeanggotaanKehormatan;
import model.KeanggotaanMahasiswa;
import model.Majalah;
import model.Peminjaman;
import model.Skripsi;
import service.PerpustakaanService;

import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

/**
 * Kompilasi dari folder Pert 9\java (Windows):
 *     javac -d out Main.java model\*.java service\*.java
 *     java -cp out Main
 *
 * (Linux/macOS: javac -d out $(find . -name "*.java"))
 */
public class Main {

    private static final Locale ID = Locale.forLanguageTag("id-ID");
    private static final DateTimeFormatter TGL = DateTimeFormatter.ofPattern("d MMMM yyyy", ID);

    public static void main(String[] args) {

        System.out.println("=== Komposisi: Buku memiliki Bab ===");
        Buku buku = new Buku("PBO-001", "Pemrograman Berorientasi Objek", "A. W. Pribadi", 2020, 3);
        buku.tambahBab(1, "Paradigma Objek");
        buku.tambahBab(2, "Enkapsulasi");
        buku.tambahBab(3, "Pewarisan dan Polimorfisme");
        System.out.println("  " + buku);
        buku.getBab().forEach(b -> System.out.println("    " + b));

        // Percobaan bukti-relasi.md nomor 1:
        System.out.println();
        System.out.println("  Mencoba mengubah koleksi internal dari luar...");
        try {
            buku.getBab().clear();
            System.out.println("    MASALAH: koleksi internal berhasil dikosongkan dari luar!");
            System.out.println("    Berarti getBab() Anda belum mengembalikan salinan.");
        } catch (UnsupportedOperationException e) {
            System.out.println("    Ditolak — koleksi bab tidak bisa diubah dari luar. Benar.");
        }

        System.out.println();
        System.out.println("=== Agregasi: Jurusan menaungi Mahasiswa ===");
        Jurusan.Mahasiswa ani  = new Jurusan.Mahasiswa("2024001", "Ani Lestari");
        Jurusan.Mahasiswa budi = new Jurusan.Mahasiswa("2024002", "Budi Santoso");

        Jurusan informatika = new Jurusan("TI", "Teknik Informatika");
        informatika.daftarkan(ani);
        informatika.daftarkan(budi);
        System.out.println("  " + informatika);

        informatika.keluarkan(budi);
        System.out.println("  Setelah Budi pindah: " + informatika);
        System.out.println("  Budi tetap ada sebagai objek: " + budi);
        System.out.println();
        System.out.println("  Itulah bedanya: Bab tidak bisa hidup di luar Buku,");
        System.out.println("  tetapi Mahasiswa tetap ada di luar Jurusan.");

        // ── Layanan perpustakaan: aturan bisnis dari deskripsi-kebutuhan.md ──
        System.out.println();
        System.out.println("=== Layanan: koleksi dibuat di luar, lalu diagregasi ===");
        PerpustakaanService layanan = new PerpustakaanService();
        Buku pbo = buku;                                         // objek yang sama dengan di atas
        Buku basisData = new Buku("BD-001", "Sistem Basis Data", "R. Elmasri", 2016, 2);
        Majalah majalah = new Majalah("MJ-001", "Majalah Teknologi", "Redaksi", 2026, 1);
        Skripsi skripsi = new Skripsi("SK-001", "Analisis Sistem Informasi", "Mahasiswa TI", 2025, 1);
        layanan.tambahKoleksi(pbo);
        layanan.tambahKoleksi(basisData);
        layanan.tambahKoleksi(majalah);
        layanan.tambahKoleksi(skripsi);

        Anggota aniAnggota = new Anggota("A001", "Ani Lestari", new KeanggotaanMahasiswa());
        Anggota dosen      = new Anggota("D001", "Dr. Rina Pratama", new KeanggotaanDosen());
        Anggota tamu       = new Anggota("K001", "Pak Hasan", new KeanggotaanKehormatan());
        layanan.daftarkanAnggota(aniAnggota);
        layanan.daftarkanAnggota(dosen);
        layanan.daftarkanAnggota(tamu);
        System.out.println("  Koleksi: 4, anggota: 3");

        LocalDate awal = LocalDate.of(2026, 10, 1);
        System.out.println();
        System.out.println("=== Peminjaman dan jatuh tempo (dipinjam " + awal.format(TGL) + ") ===");
        cetakPinjam(layanan.pinjam("A001", "PBO-001", awal));
        cetakPinjam(layanan.pinjam("A001", "MJ-001", awal));
        cetakPinjam(layanan.pinjam("D001", "BD-001", awal));
        System.out.println("  Eksemplar PBO-001 kini: " + pbo.getJumlahEksemplar()
                + ", status " + pbo.getStatus().getLabel());

        System.out.println();
        System.out.println("=== Aturan yang harus menolak ===");
        coba("Pinjam skripsi",            () -> layanan.pinjam("A001", "SK-001", awal));
        coba("Majalah sudah habis",       () -> layanan.pinjam("K001", "MJ-001", awal));
        layanan.pinjam("A001", "BD-001", awal);                  // pinjaman ke-3 Ani
        coba("Pinjaman ke-4 Ani",         () -> layanan.pinjam("A001", "PBO-001", awal));
        majalah.tandaiRusak();
        coba("Majalah berstatus rusak",   () -> layanan.pinjam("K001", "MJ-001", awal));
        coba("Koleksi tidak dikenal",     () -> layanan.pinjam("A001", "XX-999", awal));

        System.out.println();
        System.out.println("=== Pengembalian terlambat dan denda (kembali "
                + LocalDate.of(2026, 10, 20).format(TGL) + ") ===");
        LocalDate kembali = LocalDate.of(2026, 10, 20);
        cetakKembali(layanan.kembalikan("A001", "PBO-001", kembali));
        cetakKembali(layanan.kembalikan("D001", "BD-001", kembali));
        layanan.pinjam("K001", "PBO-001", awal);
        cetakKembali(layanan.kembalikan("K001", "PBO-001", kembali));
        coba("Kembalikan yang tidak dipinjam", () -> layanan.kembalikan("D001", "PBO-001", kembali));

        System.out.println();
        System.out.println("=== Sedang dipinjam sekarang ===");
        for (Peminjaman p : layanan.daftarSedangDipinjam()) {
            System.out.println("  " + p.getAnggota().getNama() + " -> " + p.getKoleksi().getJudul()
                    + " (jatuh tempo " + p.getTanggalJatuhTempo().format(TGL) + ")");
        }
        System.out.println("  Riwayat Ani: " + layanan.riwayatAnggota("A001").size() + " peminjaman");
    }

    private static void cetakPinjam(Peminjaman p) {
        System.out.println("  " + p.getAnggota().getNama() + " meminjam " + p.getKoleksi().getJudul()
                + ", jatuh tempo " + p.getTanggalJatuhTempo().format(TGL));
    }

    private static void cetakKembali(Peminjaman p) {
        System.out.println("  " + p.getAnggota().getNama() + " (" + p.getAnggota().getJenis().nama() + ") "
                + "mengembalikan " + p.getKoleksi().getJudul() + ": terlambat " + p.hariTerlambat()
                + " hari, denda " + rupiah(p.hitungDenda()));
    }

    private static String rupiah(long nilai) {
        return String.format(ID, "Rp%,d", nilai);
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
