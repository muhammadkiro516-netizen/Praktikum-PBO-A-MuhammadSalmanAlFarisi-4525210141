package model;

/**
 * Jenis keanggotaan dibuat sebagai KONTRAK (bukan enum + switch), karena
 * deskripsi kebutuhan menyebut akan ada jenis baru di kemudian hari.
 * Jenis baru cukup menambah implementasi; Peminjaman dan Anggota tidak disentuh
 * (prinsip Open-Closed, sama seperti pertemuan 5).
 */
public interface JenisKeanggotaan {

    String nama();

    /** Denda per hari keterlambatan, dalam rupiah. */
    int dendaPerHari();
}
