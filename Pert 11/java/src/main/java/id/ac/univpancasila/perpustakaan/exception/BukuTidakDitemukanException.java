package id.ac.univpancasila.perpustakaan.exception;

/** ISBN tidak terdaftar. Membawa isbn. */
public class BukuTidakDitemukanException extends PerpustakaanException {

    private final String isbn;

    public BukuTidakDitemukanException(String isbn) {
        super("Buku dengan ISBN " + isbn + " tidak ditemukan");
        this.isbn = isbn;
    }

    public String getIsbn() { return isbn; }
}
