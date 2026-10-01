public abstract class BangunDatar {
    public abstract double luas();

    // Latihan E.1: method STATIC di kelas induk.
    public static double hitungLuas() {
        System.out.println("    [dijalankan] BangunDatar.hitungLuas()  (static milik induk)");
        return 0;
    }
}
