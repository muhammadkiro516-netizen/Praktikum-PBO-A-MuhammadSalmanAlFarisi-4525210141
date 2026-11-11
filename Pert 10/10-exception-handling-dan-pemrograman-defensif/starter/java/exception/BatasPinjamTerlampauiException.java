package id.ac.univpancasila.perpustakaan.exception;

/**
 * TODO: exception ini membawa idAnggota dan batas maksimum.
 *       Menyertakan batas di dalam exception memudahkan pemanggil
 *       menyusun pesan tanpa harus mengetahui aturannya sendiri.
 */
public class BatasPinjamTerlampauiException extends PerpustakaanException {

    public BatasPinjamTerlampauiException(String idAnggota, int batas) {
        super("TODO belum dikerjakan");
    }
}
