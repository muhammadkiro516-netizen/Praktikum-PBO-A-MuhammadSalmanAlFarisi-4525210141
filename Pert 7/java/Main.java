import model.*;
import service.PerpustakaanService;

import java.time.LocalDate;
import java.util.List;

/**
 * Kompilasi dari folder Pert 7/java:
 *     javac -d out $(find . -name "*.java")
 *     java -cp out Main
 */
public class Main {

    public static void main(String[] args) {
        PerpustakaanService layanan = new PerpustakaanService();

        // ---------------------------------------------------------------
        System.out.println("=== Komposisi: Buku memiliki Bab ===");
        Buku pbo = new Buku("PBO-001", "Pemrograman Berorientasi Objek", "A. W. Pribadi", 2024, 3,
                List.of("Paradigma Objek", "Enkapsulasi", "Pewarisan dan Polimorfisme"));
        System.out.println("  " + pbo);
        pbo.getBab().forEach(b -> System.out.println("    " + b));

        System.out.println();
        System.out.println("  Mencoba mengubah koleksi internal dari luar...");
        try {
            pbo.getBab().clear();
            System.out.println("    MASALAH: koleksi internal berhasil dikosongkan dari luar!");
        } catch (UnsupportedOperationException e) {
            System.out.println("    Ditolak - koleksi bab tidak bisa diubah dari luar. Benar.");
        }
        // Bab tidak bisa dibuat dari luar Buku. Baris ini tidak lolos kompilasi
        // (konstruktor Bab privat), lihat percobaan/03-bab-dari-luar:
        // Buku.Bab liar = new Buku.Bab(9, "Bab liar");

        System.out.println();
        System.out.println("  Multiplicity 1..*: buku tanpa bab ditolak constructor");
        try {
            new Buku("PBO-X", "Buku Kosong", "Anonim", 2024, 1, List.of());
        } catch (IllegalArgumentException e) {
            System.out.println("    Ditolak: " + e.getMessage());
        }

        // ---------------------------------------------------------------
        System.out.println();
        System.out.println("=== Agregasi: PerpustakaanService menaungi Koleksi dan Anggota ===");
        Majalah majalah = new Majalah("MJ-014", "Majalah Informatika Edisi September", "Redaksi", 2026, 2);
        Skripsi skripsi = new Skripsi("SK-203", "Sistem Informasi Perpustakaan", "Dewi Kartika", 2025, 1);
        Buku rusak = new Buku("PBO-002", "Algoritma Dasar", "B. Santoso", 2020, 1, List.of("Pengantar"));
        rusak.tandaiRusak();

        layanan.tambahKoleksi(pbo);
        layanan.tambahKoleksi(majalah);
        layanan.tambahKoleksi(skripsi);
        layanan.tambahKoleksi(rusak);
        layanan.getKoleksi().forEach(k -> System.out.println("  " + k));

        Anggota ani  = new Anggota("A-001", "Ani Lestari", new Mahasiswa());
        Anggota budi = new Anggota("A-002", "Budi Santoso", new Dosen());
        Anggota cici = new Anggota("A-003", "Cici Hartono", new AnggotaKehormatan());
        layanan.daftarkanAnggota(ani);
        layanan.daftarkanAnggota(budi);
        layanan.daftarkanAnggota(cici);

        System.out.println();
        System.out.println("  Mengeluarkan skripsi dari katalog...");
        Koleksi dilepas = layanan.hapusKoleksi("SK-203");
        System.out.println("    Katalog sekarang berisi " + layanan.getKoleksi().size() + " koleksi");
        System.out.println("    Objeknya tetap hidup di luar layanan: " + dilepas.getJudul());
        layanan.tambahKoleksi(dilepas);   // dimasukkan lagi, membuktikan objek yang sama
        System.out.println("    Dimasukkan kembali, katalog berisi " + layanan.getKoleksi().size() + " koleksi");

        // ---------------------------------------------------------------
        System.out.println();
        System.out.println("=== Peminjaman dan aturan bisnis ===");
        LocalDate hari0 = LocalDate.of(2026, 10, 1);

        Peminjaman p1 = layanan.pinjam("A-001", "PBO-001", hari0);
        System.out.println("  " + p1);
        System.out.println("  Sisa eksemplar PBO-001: " + pbo.getJumlahEksemplar());

        tolak("Skripsi tidak boleh dipinjam", () -> layanan.pinjam("A-001", "SK-203", hari0));
        tolak("Koleksi rusak tidak boleh dipinjam", () -> layanan.pinjam("A-001", "PBO-002", hari0));

        layanan.pinjam("A-001", "MJ-014", hari0);
        layanan.pinjam("A-002", "MJ-014", hari0);
        tolak("Majalah habis (2 eksemplar sudah dipinjam)", () -> layanan.pinjam("A-003", "MJ-014", hari0));

        layanan.pinjam("A-001", "PBO-001", hari0.plusDays(1));
        tolak("Pinjaman keempat Ani melewati batas tiga", () -> layanan.pinjam("A-001", "PBO-001", hari0));

        System.out.println();
        System.out.println("  Sedang dipinjam:");
        layanan.daftarSedangDipinjam().forEach(p -> System.out.println("    " + p));

        // ---------------------------------------------------------------
        System.out.println();
        System.out.println("=== Pengembalian dan denda ===");
        // Buku: tempo = 1 Okt + 14 = 15 Okt. Dikembalikan 20 Okt -> terlambat 5 hari.
        long dendaAni = layanan.kembalikan("A-001", "PBO-001", LocalDate.of(2026, 10, 20));
        System.out.println("  Ani (Mahasiswa, Rp1.000/hari), 5 hari terlambat -> " + rupiah(dendaAni));
        // Majalah: tempo = 1 Okt + 3 = 4 Okt. Dikembalikan 14 Okt -> terlambat 10 hari.
        long dendaBudi = layanan.kembalikan("A-002", "MJ-014", LocalDate.of(2026, 10, 14));
        System.out.println("  Budi (Dosen, Rp500/hari), 10 hari terlambat -> " + rupiah(dendaBudi));

        // Anggota kehormatan bebas denda; pakai majalah yang kini kembali tersedia.
        layanan.pinjam("A-003", "MJ-014", hari0);
        long dendaCici = layanan.kembalikan("A-003", "MJ-014", LocalDate.of(2026, 10, 14));
        System.out.println("  Cici (Kehormatan, bebas denda), 10 hari terlambat -> " + rupiah(dendaCici));

        tolak("Mengembalikan koleksi yang tidak dipinjam", () -> layanan.kembalikan("A-002", "PBO-001", hari0));

        System.out.println();
        System.out.println("  Riwayat Ani:");
        layanan.riwayat("A-001").forEach(p -> System.out.println("    " + p));

        // ---------------------------------------------------------------
        System.out.println();
        System.out.println("=== Jenis keanggotaan baru tanpa menyunting kelas lama ===");
        record Alumni() implements JenisKeanggotaan {
            public String nama()      { return "Alumni"; }
            public int dendaPerHari() { return 750; }
        }
        Anggota dedi = new Anggota("A-004", "Dedi Kurnia", new Alumni());
        layanan.daftarkanAnggota(dedi);
        layanan.pinjam("A-004", "MJ-014", hari0);
        long dendaDedi = layanan.kembalikan("A-004", "MJ-014", LocalDate.of(2026, 10, 14));
        System.out.println("  Dedi (Alumni, Rp750/hari), 10 hari terlambat -> " + rupiah(dendaDedi));
        System.out.println("  Tidak ada satu pun kelas lama yang disunting.");
    }

    private static String rupiah(long jumlah) {
        return "Rp" + String.format(java.util.Locale.of("id", "ID"), "%,d", jumlah);
    }

    /** Menjalankan aksi yang HARUS ditolak dan mencetak alasannya. */
    private static void tolak(String keterangan, Runnable aksi) {
        try {
            aksi.run();
            System.out.println("  MASALAH: seharusnya ditolak -> " + keterangan);
        } catch (IllegalStateException | IllegalArgumentException e) {
            System.out.println("  Ditolak (" + keterangan + "): " + e.getMessage());
        }
    }
}
