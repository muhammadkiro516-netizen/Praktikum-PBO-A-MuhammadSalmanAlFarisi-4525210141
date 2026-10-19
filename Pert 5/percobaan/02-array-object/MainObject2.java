public class MainObject2 {
    public static void main(String[] args) {
        Object[] daftar = { new Lingkaran(7), new Persegi(5) };

        double total = 0;
        for (Object b : daftar) total += b.luas();   // variabel bertipe Object
        System.out.printf("%n  Total luas: %.2f%n", total);
    }
}
