import exception.AnggotaTidakDitemukanException;
import exception.BatasPinjamTerlampauiException;
import exception.BukuSedangDipinjamException;
import exception.BukuTidakDitemukanException;
import exception.PerpustakaanException;

import java.io.IOException;
import java.util.Scanner;

/**
 * Antarmuka konsol — menunjukkan:
 *   1. Tangkap seluruh PerpustakaanException dengan satu blok catch.
 *   2. Program tidak berhenti karena masukan salah.
 *   3. Ekspor daftar buku dengan try-with-resources.
 *   4. Pencatatan exception ke file log.
 *
 * Kompilasi (dari folder java/):
 *   javac -d out exception/*.java LibraryServiceKotor.java LibraryService.java Main.java
 *   java -cp out Main
 */
public class Main {

    public static void main(String[] args) {
        LibraryService layanan = new LibraryService();

        // Data awal
        layanan.tambahBuku("978-0-13-468599-1", "Effective Java",              2);
        layanan.tambahBuku("978-0-13-110362-7", "The C Programming Language",  1);
        layanan.tambahBuku("978-0-20-163361-5", "The Pragmatic Programmer",    1);
        layanan.daftarkanAnggota("A001");
        layanan.daftarkanAnggota("A002");

        Scanner sc = new Scanner(System.in);

        System.out.println("=== Sistem Perpustakaan — Sesi 10: Exception Handling ===");
        System.out.println("Ketik masukan sembarang untuk menguji ketahanan program.");
        System.out.println();

        // Demo otomatis semua skenario kegagalan
        System.out.println("--- Demo otomatis: skenario kegagalan ---");
        demo(layanan, "A001", "978-0-13-468599-1");   // berhasil
        demo(layanan, "TIDAK_ADA", "978-0-13-468599-1"); // AnggotaTidakDitemukan
        demo(layanan, "A001", "ISBN-TIDAK-ADA");          // BukuTidakDitemukan
        // Habiskan stok buku kedua
        demo(layanan, "A001", "978-0-13-110362-7");   // berhasil (stok 1)
        demo(layanan, "A002", "978-0-13-110362-7");   // BukuSedangDipinjam
        // Capai batas pinjam A001 (sudah 2, tambah 1 lagi = 3, lalu coba ke-4)
        demo(layanan, "A001", "978-0-20-163361-5");   // berhasil (pinjaman ke-3)
        demo(layanan, "A001", "978-0-13-468599-1");   // BatasPinjamTerlampaui (stok ada, tapi batas 3)

        // Demo ekspor try-with-resources
        System.out.println();
        System.out.println("--- Demo ekspor daftar buku (try-with-resources) ---");
        try {
            layanan.eksporDaftarBuku("daftar-buku.txt");
            System.out.println("  Ekspor berhasil -> daftar-buku.txt");
        } catch (IOException e) {
            System.out.println("  Gagal ekspor: " + e.getMessage());
        }

        // Antarmuka konsol interaktif
        System.out.println();
        System.out.println("--- Mode interaktif (ketik 'keluar' untuk berhenti) ---");
        while (true) {
            System.out.print("ISBN  : ");
            String isbn = sc.nextLine().trim();
            if (isbn.equalsIgnoreCase("keluar")) break;

            System.out.print("ID    : ");
            String id = sc.nextLine().trim();
            if (id.equalsIgnoreCase("keluar")) break;

            if (isbn.isEmpty() || id.isEmpty()) {
                System.out.println("  [!] ISBN dan ID tidak boleh kosong.");
                continue;
            }

            try {
                layanan.pinjam(id, isbn);
                System.out.println("  [OK] Peminjaman berhasil: " + isbn + " oleh " + id);
            } catch (PerpustakaanException e) {
                layanan.log(e);
                // Tangkap satu blok, ambil data spesifik bila perlu
                if (e instanceof BukuTidakDitemukanException bte) {
                    System.out.println("  [!] Buku tidak ada: ISBN=" + bte.getIsbn());
                } else if (e instanceof AnggotaTidakDitemukanException ate) {
                    System.out.println("  [!] Anggota tidak ada: ID=" + ate.getIdAnggota());
                } else if (e instanceof BukuSedangDipinjamException bsd) {
                    System.out.println("  [!] Stok habis: \"" + bsd.getJudul() + "\" (ISBN=" + bsd.getIsbn() + ")");
                } else if (e instanceof BatasPinjamTerlampauiException bpt) {
                    System.out.println("  [!] Batas pinjam: " + bpt.getBatas() + " koleksi (ID=" + bpt.getIdAnggota() + ")");
                } else {
                    System.out.println("  [!] Kegagalan: " + e.getMessage());
                }
            }
        }
        System.out.println("Program selesai.");
    }

    private static void demo(LibraryService layanan, String id, String isbn) {
        try {
            layanan.pinjam(id, isbn);
            System.out.printf("  [OK] %s meminjam ISBN %s%n", id, isbn);
        } catch (PerpustakaanException e) {
            layanan.log(e);
            System.out.printf("  [!] %-42s -> %s%n", e.getClass().getSimpleName(), e.getMessage());
        }
    }
}
