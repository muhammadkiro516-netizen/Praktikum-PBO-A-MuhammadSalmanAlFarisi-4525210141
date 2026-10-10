import java.util.List;

public class Main {

    /**
     * Perhatikan tipe parameternya: Fuelable, bukan Mobil.
     * Method ini tidak peduli kelas konkretnya, hanya peduli kontraknya.
     * Kelas baru yang implements Fuelable langsung bisa dipakai di sini,
     * tanpa menyunting satu baris pun.
     */
    static void isiPenuh(Fuelable kendaraan) {
        kendaraan.isiBahanBakar(kendaraan.kapasitasTangki());
        double biaya = kendaraan.tipeBahanBakar().biayaPengisian(kendaraan.kapasitasTangki());
        System.out.printf("  Diisi penuh %s - biaya Rp%,.0f%n",
                kendaraan.tipeBahanBakar().getLabel(), biaya);
    }

    public static void main(String[] args) {
        Mobil mobil = new Mobil("Toyota Avanza", 2022, 45);
        Sepeda sepeda = new Sepeda("Polygon Heist", 2023);
        Genset genset = new Genset("Genset Honda", 20);

        // Checkpoint Langkah 3: satu objek Mobil bisa ditampung tiga tipe variabel.
        System.out.println("=== Satu objek Mobil, tiga tipe variabel ===");
        Kendaraan sebagaiKendaraan = mobil;
        Movable   sebagaiMovable   = mobil;
        Fuelable  sebagaiFuelable  = mobil;
        System.out.println("  Kendaraan : " + sebagaiKendaraan + ", umur " + sebagaiKendaraan.umur(2026) + " tahun");
        System.out.println("  Movable   : kecepatan maksimum " + sebagaiMovable.kecepatanMaksimum() + " km/jam");
        System.out.println("  Fuelable  : kapasitas tangki " + sebagaiFuelable.kapasitasTangki() + " liter");

        System.out.println();
        System.out.println("=== Semua Movable ===");
        // Langkah 4: Sepeda ditambahkan ke daftar. Perulangan tidak diubah.
        for (Movable m : List.of(mobil, sepeda)) {
            m.bergerak();
            System.out.println("    " + m.ringkasanGerak());
        }

        System.out.println();
        System.out.println("=== Hanya yang Fuelable ===");
        isiPenuh(mobil);
        isiPenuh(genset);   // pertanyaan demo 3: Genset tidak bergerak, tetapi Fuelable

        // Langkah 4: baris di bawah SENGAJA dibiarkan menjadi komentar supaya program ini
        // bisa dikompilasi. Bila komentarnya dihapus, kompilasi gagal. Pesan kesalahan
        // lengkapnya ada di percobaan/01-sepeda-ditolak dan keputusan.md.
        // isiPenuh(sepeda);

        System.out.println();
        System.out.println("=== Enum punya perilaku ===");
        for (TipeBahanBakar t : TipeBahanBakar.values()) {
            System.out.printf("  %-8s ramah lingkungan? %-5s  biaya 10 satuan: Rp%,.0f%n",
                    t.getLabel(), t.ramahLingkungan(), t.biayaPengisian(10));
        }
    }
}
