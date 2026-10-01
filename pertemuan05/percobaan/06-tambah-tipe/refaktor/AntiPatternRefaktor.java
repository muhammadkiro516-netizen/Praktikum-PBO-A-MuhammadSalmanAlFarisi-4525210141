/**
 * LANGKAH 5 — hasil refaktor AntiPattern.java menjadi polimorfik.
 *
 * Pengetahuan "cara menghitung luas" dipindahkan dari satu method besar
 * (hitungLuas dengan rantai if-else pemeriksaan tipe) ke dalam setiap tipe data itu sendiri.
 * Tidak ada satu pun pemeriksaan tipe di berkas ini.
 *
 * Berkas asli AntiPattern.java tetap dipertahankan untuk pembanding saat demo.
 */
public class AntiPatternRefaktor {

    /** Kontrak: apa pun yang berbentuk "bangun" wajib bisa menghitung luasnya sendiri. */
    interface Bangun {
        double luas();
    }

    record LingkaranData(double r) implements Bangun {
        @Override public double luas() { return Math.PI * r * r; }
    }

    record PersegiData(double sisi) implements Bangun {
        @Override public double luas() { return sisi * sisi; }
    }

    record SegitigaData(double alas, double tinggi) implements Bangun {
        @Override public double luas() { return 0.5 * alas * tinggi; }
    }

    record TrapesiumData(double a, double b, double t) implements Bangun {
        @Override public double luas() { return (a + b) / 2 * t; }
    }

    public static void main(String[] args) {
        // Upcasting: variabel bertipe antarmuka, objek bertipe record.
        Bangun[] daftar = {
            new LingkaranData(7),
            new PersegiData(5),
            new SegitigaData(4, 3),
            new TrapesiumData(4, 10, 4)
        };

        double total = 0;
        for (Bangun b : daftar) total += b.luas();
        System.out.printf("Total luas (cara polimorfik): %.2f%n", total);
    }
}
