import exception.AnggotaTidakDitemukanException;
import exception.BatasPinjamTerlampauiException;
import exception.BukuSedangDipinjamException;
import exception.BukuTidakDitemukanException;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Sesi 10 — versi refaktor dengan guard clause dan custom exception.
 * Bandingkan pinjam() di sini dengan LibraryServiceKotor.pinjam().
 */
public class LibraryService {

    public static final int BATAS_PINJAM = 3;

    private final Map<String, Integer> stok   = new HashMap<>();
    private final Map<String, String>  judul  = new HashMap<>();
    private final Map<String, Integer> pinjaman = new HashMap<>();

    private static final String LOG_FILE = "perpustakaan.log";
    private static final DateTimeFormatter FMT =
            DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");

    public void tambahBuku(String isbn, String judulBuku, int eksemplar) {
        stok.merge(isbn, eksemplar, Integer::sum);
        judul.put(isbn, judulBuku);
    }

    public void daftarkanAnggota(String idAnggota) {
        pinjaman.putIfAbsent(idAnggota, 0);
    }

    /**
     * Guard clause: periksa yang salah lebih dulu — lempar exception —
     * sehingga logika utama tetap rata di kiri, tidak bersarang.
     */
    public void pinjam(String idAnggota, String isbn) {
        if (!pinjaman.containsKey(idAnggota))
            throw new AnggotaTidakDitemukanException(idAnggota);

        if (!stok.containsKey(isbn))
            throw new BukuTidakDitemukanException(isbn);

        if (stok.get(isbn) <= 0)
            throw new BukuSedangDipinjamException(isbn, judul.get(isbn));

        if (pinjaman.get(idAnggota) >= BATAS_PINJAM)
            throw new BatasPinjamTerlampauiException(idAnggota, BATAS_PINJAM);

        stok.merge(isbn, -1, Integer::sum);
        pinjaman.merge(idAnggota, 1, Integer::sum);
    }

    public int sisaEksemplar(String isbn) { return stok.getOrDefault(isbn, 0); }
    public String judulDari(String isbn)  { return judul.get(isbn); }
    public List<String> semuaIsbn()       { return Collections.unmodifiableList(new ArrayList<>(stok.keySet())); }

    /** Ekspor daftar buku ke file — memakai try-with-resources. */
    public void eksporDaftarBuku(String namaFile) throws IOException {
        try (BufferedWriter bw = new BufferedWriter(new FileWriter(namaFile))) {
            bw.write("=== Daftar Buku Perpustakaan ===");
            bw.newLine();
            for (String isbn : stok.keySet()) {
                bw.write(String.format("ISBN: %-20s | Judul: %-40s | Stok: %d",
                        isbn, judul.get(isbn), stok.get(isbn)));
                bw.newLine();
            }
        }
        // BufferedWriter ditutup otomatis meski exception terjadi di dalam blok
    }

    /** Catat exception ke file log. */
    public void log(Exception e) {
        try (BufferedWriter bw = new BufferedWriter(new FileWriter(LOG_FILE, true))) {
            bw.write("[" + LocalDateTime.now().format(FMT) + "] "
                    + e.getClass().getSimpleName() + ": " + e.getMessage());
            bw.newLine();
        } catch (IOException io) {
            System.err.println("Gagal menulis log: " + io.getMessage());
        }
    }
}
