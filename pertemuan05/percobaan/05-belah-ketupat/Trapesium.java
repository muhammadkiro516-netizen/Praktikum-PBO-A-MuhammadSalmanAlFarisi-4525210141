/**
 * Langkah 4 — bukti Open-Closed Principle.
 * Kelas ini DITAMBAHKAN; tidak ada kelas lama yang disunting.
 *
 * alasAtas dan alasBawah adalah dua sisi sejajar, kakiKiri dan kakiKanan adalah dua sisi miring.
 */
public class Trapesium extends BangunDatar {

    private final double alasAtas;
    private final double alasBawah;
    private final double tinggi;
    private final double kakiKiri;
    private final double kakiKanan;

    public Trapesium(double alasAtas, double alasBawah, double tinggi,
                     double kakiKiri, double kakiKanan) {
        super("Trapesium");
        if (alasAtas <= 0 || alasBawah <= 0 || tinggi <= 0 || kakiKiri <= 0 || kakiKanan <= 0) {
            throw new IllegalArgumentException("Semua ukuran trapesium harus lebih besar dari 0.");
        }
        // Sisi miring tidak mungkin lebih pendek dari tinggi.
        if (kakiKiri < tinggi || kakiKanan < tinggi) {
            throw new IllegalArgumentException("Sisi miring tidak boleh lebih pendek dari tinggi.");
        }
        this.alasAtas = alasAtas;
        this.alasBawah = alasBawah;
        this.tinggi = tinggi;
        this.kakiKiri = kakiKiri;
        this.kakiKanan = kakiKanan;
    }

    @Override
    public double luas() { return (alasAtas + alasBawah) / 2 * tinggi; }

    @Override
    public double keliling() { return alasAtas + alasBawah + kakiKiri + kakiKanan; }
}
