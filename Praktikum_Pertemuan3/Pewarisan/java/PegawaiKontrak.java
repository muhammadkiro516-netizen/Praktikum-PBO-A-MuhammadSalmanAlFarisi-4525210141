public class PegawaiKontrak extends Pegawai {

    private final int bulanKontrak;

    public PegawaiKontrak(String nip, String nama, double gajiPokok, int bulanKontrak) {
        super(nip, nama, gajiPokok);
        if (bulanKontrak < 0) {
            throw new IllegalArgumentException("Bulan kontrak tidak boleh negatif.");
        }
        this.bulanKontrak = bulanKontrak;
    }

    // Pegawai kontrak tidak mendapat tunjangan masa kerja sehingga
    // implementasi hitungGaji() dari induk sudah sesuai.

    @Override
    public String jenis() { return "KONTRAK"; }

    public int getBulanKontrak() { return bulanKontrak; }
}
