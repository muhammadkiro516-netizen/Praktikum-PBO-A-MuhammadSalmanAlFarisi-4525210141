public class MainObject {
    public static void main(String[] args) {
        // Latihan E.2: tipe array diubah dari BangunDatar[] menjadi Object[].
        Object[] daftar = {
            new Lingkaran(7),
            new Persegi(5),
        };

        for (BangunDatar b : daftar) {          // <- perulangan lama dibiarkan apa adanya
            System.out.println("  " + b);
        }

        double total = 0;
        for (BangunDatar b : daftar) total += b.luas();
        System.out.printf("%n  Total luas: %.2f%n", total);
    }
}
