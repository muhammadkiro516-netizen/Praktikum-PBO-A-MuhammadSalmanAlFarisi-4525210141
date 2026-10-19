/** Percobaan Langkah 4: isiPenuh(sepeda) dengan Sepeda yang BUKAN Fuelable. */
public class Main {

    static void isiPenuh(Fuelable kendaraan) {
        kendaraan.isiBahanBakar(kendaraan.kapasitasTangki());
    }

    public static void main(String[] args) {
        Sepeda sepeda = new Sepeda("Polygon Heist", 2023);
        isiPenuh(sepeda);   // <- komentar dihapus: kompilasi harus gagal
    }
}
