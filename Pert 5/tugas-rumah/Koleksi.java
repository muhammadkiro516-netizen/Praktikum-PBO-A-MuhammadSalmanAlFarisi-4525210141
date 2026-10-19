/**
 * Tugas Rumah 2 — hierarki koleksi perpustakaan.
 * Induk menetapkan kontrak biayaKeterlambatan(); tiap jenis koleksi punya aturan sendiri.
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

    /** Biaya denda untuk sejumlah hari keterlambatan. Aturannya berbeda di tiap jenis koleksi. */
    public abstract double biayaKeterlambatan(int hariTerlambat);

    public abstract String jenis();

    /** Validasi bersama, dipakai oleh semua turunan. Hari negatif tidak masuk akal. */
    protected static int pastikanHariSah(int hariTerlambat) {
        if (hariTerlambat < 0) {
            throw new IllegalArgumentException("Hari keterlambatan tidak boleh negatif.");
        }
        return hariTerlambat;
    }

    public String getKode()  { return kode; }
    public String getJudul() { return judul; }

    @Override
    public String toString() {
        return String.format("%-8s %-10s %s", kode, jenis(), judul);
    }
}
