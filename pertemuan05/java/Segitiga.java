/**
 * Langkah 2 — dibuat mandiri, tanpa starter.
 * Luas memakai rumus Heron: s = (a + b + c) / 2, luas = sqrt(s (s-a) (s-b) (s-c)).
 */
public class Segitiga extends BangunDatar {

    private final double a;
    private final double b;
    private final double c;

    public Segitiga(double a, double b, double c) {
        super("Segitiga");
        if (a <= 0 || b <= 0 || c <= 0) {
            throw new IllegalArgumentException("Setiap sisi harus lebih besar dari 0.");
        }
        // Syarat segitiga: jumlah dua sisi harus lebih besar dari sisi ketiga.
        // Dicek untuk ketiga kombinasi, dan ditolak SEBELUM objek terbentuk
        // supaya rumus Heron tidak pernah menghitung akar bilangan negatif (NaN).
        if (a + b <= c || a + c <= b || b + c <= a) {
            throw new IllegalArgumentException(
                String.format("Sisi %s, %s, %s tidak membentuk segitiga.", a, b, c));
        }
        this.a = a;
        this.b = b;
        this.c = c;
    }

    @Override
    public double luas() {
        double s = (a + b + c) / 2;
        return Math.sqrt(s * (s - a) * (s - b) * (s - c));
    }

    @Override
    public double keliling() { return a + b + c; }
}
