/**
 * Identitas: apa benda ini. Kode dan judul sama di semua koleksi,
 * sehingga ditulis sekali di abstract class (sama seperti Kendaraan di java/).
 */
public abstract class Koleksi {

    private final String kode;
    private final String judul;

    protected Koleksi(String kode, String judul) {
        if (kode == null || kode.isBlank()) {
            throw new IllegalArgumentException("Kode koleksi tidak boleh kosong.");
        }
        if (judul == null || judul.isBlank()) {
            throw new IllegalArgumentException("Judul koleksi tidak boleh kosong.");
        }
        this.kode = kode;
        this.judul = judul;
    }

    public abstract String jenis();

    public String getKode()  { return kode; }
    public String getJudul() { return judul; }

    @Override
    public String toString() {
        return String.format("%-8s %-8s %s", kode, jenis(), judul);
    }
}
