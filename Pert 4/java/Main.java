public class Main {
    public static void main(String[] args) {

        // Daftar polimorfik: satu array bertipe Pegawai berisi empat jenis pegawai.
        Pegawai[] daftar = {
            new PegawaiTetap("198701012010", "Ani Lestari",  6_000_000, 15),
            new PegawaiKontrak("K-2024-007",  "Budi Santoso", 5_000_000, 12),
            new Dosen("D-2020-015", "Citra Maharani", 7_000_000, 10, 1_500_000),
            new PegawaiHarian("H-2024-021", "Deni Saputra", 250_000, 22)
        };

        System.out.println("=== Daftar Gaji ===");
        for (Pegawai p : daftar) {
            System.out.println("  " + p);
        }

        double total = 0;
        for (Pegawai p : daftar) total += p.hitungGaji();
        System.out.printf("%n  Total beban gaji: Rp%,.2f%n", total);

        System.out.println();
        System.out.println("Periksa: Ani (pokok 6.000.000, masa kerja 15 tahun)");
        System.out.println("  tunjangan 15 x 2% = 30%, jadi gaji seharusnya Rp7.800.000,00");

        System.out.println();
        System.out.println("=== Komposisi: profil pembayaran ===");
        Pegawai ani = daftar[0];
        System.out.println("  Sebelum: " + ani.getProfilPembayaran().getBank());
        ani.setProfilPembayaran(new ProfilPembayaran("BNI", "1234567890"));
        System.out.println("  Sesudah: " + ani.getProfilPembayaran().getBank()
                           + " / " + ani.getProfilPembayaran().getNomorRekening());

        // Percobaan Langkah 1: hapus komentar baris berikut, kompilasi, catat pesannya.
        // Pegawai langsung = new Pegawai("X", "Y", 1000);
    }
}
