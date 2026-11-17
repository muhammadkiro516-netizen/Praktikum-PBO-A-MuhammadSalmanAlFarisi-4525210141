package id.ac.univpancasila.perpustakaan;

import id.ac.univpancasila.perpustakaan.model.Buku;
import id.ac.univpancasila.perpustakaan.service.LaporanBuku;
import id.ac.univpancasila.perpustakaan.service.LibraryService;

import java.util.List;

/**
 * Menampilkan tiga laporan fungsional. Jalankan dengan:
 *     mvn exec:java -Dexec.mainClass=id.ac.univpancasila.perpustakaan.LaporanApp
 */
public class LaporanApp {

    public static void main(String[] args) {
        LibraryService layanan = new LibraryService();
        layanan.tambahBuku(new Buku("978-1", "Basis Data", "R. Elmasri", 2016, 2));
        layanan.tambahBuku(new Buku("978-2", "Pemrograman Berorientasi Objek", "A. W. Pribadi", 2020, 3));
        layanan.tambahBuku(new Buku("978-3", "Jaringan Komputer", "A. Tanenbaum", 2011, 0));
        layanan.tambahBuku(new Buku("978-4", "Algoritma dan Struktur Data", "A. W. Pribadi", 2019, 1));
        layanan.tambahBuku(new Buku("978-5", "Sistem Operasi", "A. Silberschatz", 2018, 2));
        List<Buku> semua = layanan.semuaBuku();

        System.out.println("=== Laporan 1: buku yang tersedia ===");
        LaporanBuku.tersedia(semua).forEach(b ->
                System.out.println("  " + b.getNomorInduk() + "  " + b.getJudul() + "  (" + b.getJumlahEksemplar() + " eks)"));

        System.out.println();
        System.out.println("=== Laporan 2: seluruh judul ===");
        System.out.println("  " + LaporanBuku.judulGabung(semua, "; "));

        System.out.println();
        System.out.println("=== Laporan 3: jumlah buku per penulis ===");
        LaporanBuku.jumlahPerPenulis(semua).forEach((penulis, n) ->
                System.out.println("  " + penulis + ": " + n));

        System.out.println();
        System.out.println("=== Optional: pencarian yang aman ===");
        System.out.println("  978-2 -> " + layanan.cariBuku("978-2").map(Buku::getJudul).orElse("(tidak ada)"));
        System.out.println("  999   -> " + layanan.cariBuku("999").map(Buku::getJudul).orElse("(tidak ada)"));
    }
}
