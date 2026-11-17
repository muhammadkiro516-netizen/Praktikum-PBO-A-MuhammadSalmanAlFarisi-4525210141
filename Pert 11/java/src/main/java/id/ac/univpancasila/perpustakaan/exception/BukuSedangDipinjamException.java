package id.ac.univpancasila.perpustakaan.exception;

/** Semua eksemplar sedang dipinjam. Membawa isbn dan judul. */
public class BukuSedangDipinjamException extends PerpustakaanException {

    private final String isbn;
    private final String judul;

    public BukuSedangDipinjamException(String isbn, String judul) {
        super("Buku " + judul + " dengan ISBN " + isbn + " sedang dipinjam");
        this.isbn = isbn;
        this.judul = judul;
    }

    public String getIsbn()  { return isbn; }
    public String getJudul() { return judul; }
}
