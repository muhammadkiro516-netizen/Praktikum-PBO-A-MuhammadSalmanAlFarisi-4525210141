public class PercobaanStatic {
    @SuppressWarnings("static")
    public static void main(String[] args) {
        BangunDatar b = new Lingkaran(7);   // tipe variabel BangunDatar, tipe objek Lingkaran
        Lingkaran   l = new Lingkaran(7);   // tipe variabel Lingkaran,   tipe objek Lingkaran

        System.out.println("b.hitungLuas()  (variabel BangunDatar, objek Lingkaran):");
        System.out.println("    hasil = " + b.hitungLuas());

        System.out.println("l.hitungLuas()  (variabel Lingkaran, objek Lingkaran):");
        System.out.println("    hasil = " + l.hitungLuas());

        System.out.println("b.luas()        (method instance biasa, overriding sungguhan):");
        System.out.printf("    hasil = %.2f%n", b.luas());
    }
}
