public class Lingkaran extends BangunDatar {

    private final double jariJari;

    public Lingkaran(double jariJari) {
        super("Lingkaran");
        // TODO 1 (selesai): tolak jari-jari <= 0.
        if (jariJari <= 0) {
            throw new IllegalArgumentException("Jari-jari harus lebih besar dari 0.");
        }
        this.jariJari = jariJari;
    }

    // TODO 2 (selesai): luas = pi * r^2, keliling = 2 * pi * r (memakai Math.PI).
    public double Luas()     { return Math.PI * jariJari * jariJari; }
    @Override public double keliling() { return 2 * Math.PI * jariJari; }

    public double getJariJari() { return jariJari; }
}
