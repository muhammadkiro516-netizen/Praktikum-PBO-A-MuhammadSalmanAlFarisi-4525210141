package model;

/**
 * Kontrak jenis keanggotaan. Denda per hari berbeda menurut jenisnya, dan
 * perpustakaan memperkirakan akan ada jenis baru. Karena itu jenis dibuat
 * sebagai interface: jenis baru cukup menambah satu kelas implementasi,
 * tanpa menyunting Anggota, Peminjaman, maupun layanan (Open-Closed).
 */
public interface JenisKeanggotaan {
    String nama();
    int dendaPerHari();
}
