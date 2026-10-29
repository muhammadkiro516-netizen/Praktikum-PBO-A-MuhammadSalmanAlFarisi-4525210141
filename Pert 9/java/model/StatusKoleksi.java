package model;

/**
 * Status sebuah koleksi. Himpunannya tetap (tersedia, dipinjam, rusak, hilang),
 * sehingga enum lebih tepat daripada konstanta int atau String.
 */
public enum StatusKoleksi {
    TERSEDIA("Tersedia"),
    DIPINJAM("Dipinjam"),
    RUSAK("Rusak"),
    HILANG("Hilang");

    private final String label;

    StatusKoleksi(String label) {
        this.label = label;
    }

    public String getLabel() { return label; }

    /** Koleksi berstatus rusak atau hilang tidak dapat dipinjam, apa pun jenisnya. */
    public boolean bisaDipinjam() {
        return this == TERSEDIA;
    }
}
