package model;

/**
 * Status sebuah koleksi. Rusak dan hilang menutup peminjaman apa pun jenis koleksinya.
 */
public enum StatusKoleksi {
    TERSEDIA("Tersedia", true),
    DIPINJAM("Dipinjam", true),
    RUSAK("Rusak", false),
    HILANG("Hilang", false);

    private final String label;
    private final boolean membolehkanPeminjaman;

    StatusKoleksi(String label, boolean membolehkanPeminjaman) {
        this.label = label;
        this.membolehkanPeminjaman = membolehkanPeminjaman;
    }

    /** Rusak dan hilang tidak dapat dipinjam. Tersedia/dipinjam masih bergantung pada stok. */
    public boolean membolehkanPeminjaman() { return membolehkanPeminjaman; }

    public String getLabel() { return label; }
}
