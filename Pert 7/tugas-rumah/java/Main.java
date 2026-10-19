import model.*;
import service.RekapPresensi;

import java.time.LocalDate;
import java.util.List;

/**
 * Tugas rumah 1 (Sistem Presensi Kuliah). Dari folder tugas-rumah/java:
 *     javac -d out $(find . -name "*.java")
 *     java -cp out Main
 */
public class Main {
    public static void main(String[] args) {
        MataKuliah pbo = new MataKuliah("IF-203", "Pemrograman Berorientasi Objek", 3);
        Dosen dosen = new Dosen("198501012010", "Dr. Rina Wijaya");
        KelasKuliah kelas = new KelasKuliah("IF-203-A", pbo, dosen);

        System.out.println("=== Agregasi: kelas menaungi mahasiswa ===");
        Mahasiswa ani = new Mahasiswa("4525210001", "Ani Lestari");
        Mahasiswa budi = new Mahasiswa("4525210002", "Budi Santoso");
        Mahasiswa cici = new Mahasiswa("4525210003", "Cici Hartono");
        kelas.daftarkan(ani);
        kelas.daftarkan(budi);
        kelas.daftarkan(cici);
        System.out.println("  " + kelas);
        kelas.keluarkan(cici);
        System.out.println("  Setelah Cici keluar: " + kelas);
        System.out.println("  Cici tetap ada sebagai objek: " + cici);
        kelas.daftarkan(cici);

        System.out.println();
        System.out.println("=== Komposisi: pertemuan dan presensi lahir di dalam kelas ===");
        LocalDate awal = LocalDate.of(2026, 9, 7);
        for (int i = 0; i < 4; i++) {
            kelas.bukaPertemuan(awal.plusWeeks(i), "Topik " + (i + 1));
        }
        // Ani hadir semua; Budi izin sekali; Cici alpa dua kali.
        StatusKehadiran[] statusBudi = {StatusKehadiran.HADIR, StatusKehadiran.IZIN, StatusKehadiran.HADIR, StatusKehadiran.HADIR};
        StatusKehadiran[] statusCici = {StatusKehadiran.HADIR, StatusKehadiran.ALPA, StatusKehadiran.ALPA, StatusKehadiran.HADIR};
        for (int i = 1; i <= 4; i++) {
            kelas.catatKehadiran(i, ani, StatusKehadiran.HADIR);
            kelas.catatKehadiran(i, budi, statusBudi[i - 1]);
            kelas.catatKehadiran(i, cici, statusCici[i - 1]);
        }
        kelas.getPertemuan().forEach(p -> System.out.println("  " + p));

        System.out.println();
        System.out.println("  Mencoba mengubah koleksi internal dari luar...");
        try {
            kelas.getPertemuan().clear();
            System.out.println("    MASALAH: daftar pertemuan berhasil dikosongkan dari luar!");
        } catch (UnsupportedOperationException e) {
            System.out.println("    Ditolak - daftar pertemuan tidak bisa diubah dari luar.");
        }
        try {
            kelas.getPertemuan().get(0).getPresensi().clear();
            System.out.println("    MASALAH: daftar presensi berhasil dikosongkan dari luar!");
        } catch (UnsupportedOperationException e) {
            System.out.println("    Ditolak - daftar presensi tidak bisa diubah dari luar.");
        }
        // Tidak bisa dibuat dari luar (tidak lolos kompilasi):
        // new Pertemuan(1, awal, "liar");              // konstruktor package-private
        // new Pertemuan.Presensi(ani, StatusKehadiran.HADIR);   // konstruktor privat

        System.out.println();
        System.out.println("=== Rekap (dependensi) setelah 4 pertemuan ===");
        RekapPresensi rekap = new RekapPresensi();
        for (Mahasiswa m : List.of(ani, budi, cici)) {
            System.out.printf("  %-14s hadir %2d | izin %2d | alpa %2d | %5.1f%% | layak ujian: %s%n",
                    m.getNama(),
                    rekap.hitung(kelas, m, StatusKehadiran.HADIR),
                    rekap.hitung(kelas, m, StatusKehadiran.IZIN),
                    rekap.hitung(kelas, m, StatusKehadiran.ALPA),
                    rekap.persentaseKehadiran(kelas, m),
                    rekap.layakUjian(kelas, m) ? "ya" : "tidak");
        }

        System.out.println();
        System.out.println("=== Aturan bisnis ===");
        coba("Mencatat dua kali pada pertemuan yang sama",
                () -> kelas.catatKehadiran(1, ani, StatusKehadiran.HADIR));
        coba("Mencatat mahasiswa yang bukan peserta",
                () -> kelas.catatKehadiran(1, new Mahasiswa("4525219999", "Orang Luar"), StatusKehadiran.HADIR));
        coba("Mencatat pertemuan yang belum dibuka",
                () -> kelas.catatKehadiran(9, ani, StatusKehadiran.HADIR));
        for (int i = 5; i <= 16; i++) kelas.bukaPertemuan(awal.plusWeeks(i), "Topik " + i);
        coba("Membuka pertemuan ke-17",
                () -> kelas.bukaPertemuan(awal.plusWeeks(17), "Topik 17"));

    }

    private static void coba(String keterangan, Runnable aksi) {
        try {
            aksi.run();
            System.out.println("  MASALAH: seharusnya ditolak -> " + keterangan);
        } catch (IllegalStateException | IllegalArgumentException e) {
            System.out.println("  Ditolak (" + keterangan + "): " + e.getMessage());
        }
    }
}
