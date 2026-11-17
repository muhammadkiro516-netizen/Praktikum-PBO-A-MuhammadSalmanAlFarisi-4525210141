package id.ac.univpancasila.perpustakaan.service;

import id.ac.univpancasila.perpustakaan.model.Buku;

import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

/**
 * Sesi 11 Langkah 4 — tiga laporan memakai operasi fungsional (Stream),
 * tanpa satu pun perulangan for manual.
 */
public final class LaporanBuku {

    private LaporanBuku() {}

    /** 1. Filter: buku yang tersedia. */
    public static List<Buku> tersedia(List<Buku> buku) {
        return buku.stream()
                .filter(Buku::tersedia)
                .toList();
    }

    /** 2. Map + join: judul saja, digabung dengan pemisah. */
    public static String judulGabung(List<Buku> buku, String pemisah) {
        return buku.stream()
                .map(Buku::getJudul)
                .collect(Collectors.joining(pemisah));
    }

    /** 3. groupingBy: jumlah buku per penulis (urutan kemunculan dijaga). */
    public static Map<String, Long> jumlahPerPenulis(List<Buku> buku) {
        return buku.stream()
                .collect(Collectors.groupingBy(Buku::getPenulis, LinkedHashMap::new, Collectors.counting()));
    }
}
