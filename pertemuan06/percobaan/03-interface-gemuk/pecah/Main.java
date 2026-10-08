public class Main {
    static void nyalakanSemua(Switchable... daftar) {
        for (Switchable s : daftar) s.hidupkan();
    }

    public static void main(String[] args) {
        nyalakanSemua(new Lampu(), new Printer());
        new Printer().cetak("Laporan praktikum 6");
    }
}
