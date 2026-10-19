public class Lingkaran extends BangunDatar {
    private final double r;
    public Lingkaran(double r) { this.r = r; }

    @Override public double luas() { return Math.PI * r * r; }

    // "Override" static di turunan: sebenarnya HIDING (menutupi), bukan overriding.
    public static double hitungLuas() {
        System.out.println("    [dijalankan] Lingkaran.hitungLuas()    (static milik turunan)");
        return -1;
    }
}
