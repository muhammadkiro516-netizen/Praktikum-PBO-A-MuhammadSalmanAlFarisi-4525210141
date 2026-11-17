package id.ac.univpancasila.perpustakaan.exception;

/**
 * Induk seluruh exception domain perpustakaan (sesi 10). Diturunkan dari
 * RuntimeException (unchecked): kegagalannya menandakan permintaan yang tidak
 * sah, bukan keadaan luar yang wajar.
 */
public abstract class PerpustakaanException extends RuntimeException {

    protected PerpustakaanException(String pesan) {
        super(pesan);
    }

    protected PerpustakaanException(String pesan, Throwable penyebab) {
        super(pesan, penyebab);
    }
}
