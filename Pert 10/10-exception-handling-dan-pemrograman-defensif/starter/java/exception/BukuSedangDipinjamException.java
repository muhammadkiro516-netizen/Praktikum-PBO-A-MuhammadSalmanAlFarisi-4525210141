package id.ac.univpancasila.perpustakaan.exception;

/**
 * TODO: exception ini membawa DUA data — isbn dan judul.
 *       Judul disertakan agar pesan untuk pengguna lebih berarti;
 *       isbn disertakan agar sistem dapat mengolahnya.
 */
public class BukuSedangDipinjamException extends PerpustakaanException {

    public BukuSedangDipinjamException(String isbn, String judul) {
        super("TODO belum dikerjakan");
    }
}
