public class Main {
    public static void main(String[] args) {

        /*
         * TODO SOAL PEWARISAN
         * 1. Buat kelas induk abstrak Pegawai yang menyimpan nip, nama, dan gaji pokok.
         * 2. Tolak gaji pokok negatif melalui validasi constructor.
         * 3. Buat PegawaiTetap dengan tunjangan masa kerja maksimal 40 persen.
         * 4. Buat PegawaiKontrak tanpa tunjangan masa kerja.
         * 5. Buat Dosen sebagai turunan PegawaiTetap dengan tunjangan fungsional.
         * 6. Buat PegawaiHarian dengan perhitungan tarif per hari dikali hari kerja.
         * 7. Gunakan overriding dan panggil super.hitungGaji() pada kelas turunan.
         * 8. Tambahkan satu relasi komposisi dan jelaskan alasannya di justifikasi.md.
         * 9. Buat class diagram PlantUML dan implementasikan juga dalam PHP.
         * Status: seluruh soal sudah diimplementasikan di bawah ini.
         */

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

    }
}
