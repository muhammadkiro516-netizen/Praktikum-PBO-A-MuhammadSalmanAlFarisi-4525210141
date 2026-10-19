/**
 * Abstract class: menampung kode yang BENAR-BENAR SAMA di semua kendaraan.
 * Bandingkan perannya dengan interface Movable dan Fuelable.
 */
public abstract class Kendaraan {

    protected final String merek;
    protected final int tahun;

    protected Kendaraan(String merek, int tahun) {
        if (merek == null || merek.isBlank()) {
            throw new IllegalArgumentException("Merek tidak boleh kosong.");
        }
        if (tahun <= 0) {
            throw new IllegalArgumentException("Tahun harus lebih besar dari 0.");
        }
        this.merek = merek;
        this.tahun = tahun;
    }

    /** TODO 1 (selesai): umur kendaraan, tidak boleh negatif. */
    public int umur(int tahunSekarang) {
        return Math.max(0, tahunSekarang - tahun);
    }

    public abstract int jumlahRoda();

    public String getMerek() { return merek; }
    public int getTahun()    { return tahun; }

    @Override
    public String toString() {
        return String.format("%s (%d, %d roda)", merek, tahun, jumlahRoda());
    }
}
