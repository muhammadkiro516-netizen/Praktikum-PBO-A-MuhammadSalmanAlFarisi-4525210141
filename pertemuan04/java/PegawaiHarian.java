public class PegawaiHarian extends Pegawai {

    private final int jumlahHariKerja;

    public PegawaiHarian(String nip, String nama, double tarifPerHari, int jumlahHariKerja) {
        super(nip, nama, tarifPerHari);
        if (jumlahHariKerja < 0) {
            throw new IllegalArgumentException("Jumlah hari kerja tidak boleh negatif.");
        }
        this.jumlahHariKerja = jumlahHariKerja;
    }

    @Override
    public double hitungGaji() {
        return super.hitungGaji() * jumlahHariKerja;
    }

    @Override
    public String jenis() { return "HARIAN"; }
}