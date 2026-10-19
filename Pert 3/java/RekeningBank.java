/**
 * Sesi 3 — constructor berdelegasi, anggota statis, dan konstanta.
 *
 * Invariant:
 *   1. saldo tidak pernah negatif
 *   2. nomor rekening tidak berubah setelah objek dibuat
 *   3. setoran dan penarikan selalu bernilai positif
 */
public class RekeningBank {

    // Konstanta pengganti angka ajaib (tidak ada literal angka di badan method).
    public static final double BUNGA_TAHUNAN = 0.025;
    public static final double BIAYA_ADMINISTRASI = 5000;
    public static final double BATAS_PENARIKAN_SEKALI = 5_000_000;

    // Field statis: satu salinan untuk seluruh kelas, bukan per objek.
    private static int jumlahRekening = 0;

    private final String nomor;
    private final String pemilik;
    private double saldo;

    /** Constructor ringkas: mendelegasikan ke constructor lengkap, tanpa menyalin validasi. */
    public RekeningBank(String nomor, String pemilik) {
        this(nomor, pemilik, 0);
    }

    /** Constructor lengkap — satu-satunya tempat validasi dan penambahan penghitung. */
    public RekeningBank(String nomor, String pemilik, double saldoAwal) {
        if (nomor == null || nomor.trim().isEmpty()) {
            throw new IllegalArgumentException("Nomor rekening tidak boleh kosong");
        }
        if (saldoAwal < 0) {
            throw new IllegalArgumentException("Saldo awal tidak boleh negatif");
        }

        this.nomor = nomor;
        this.pemilik = pemilik;
        this.saldo = saldoAwal;

        // Naik di sini saja: constructor ringkas sudah lewat sini lewat this(...),
        // jadi kalau juga dinaikkan di sana hasilnya dihitung dua kali.
        jumlahRekening++;
    }

    public void setor(double jumlah) {
        if (jumlah <= 0) {
            throw new IllegalArgumentException("Jumlah setoran harus lebih dari 0");
        }
        saldo += jumlah;
    }

    public void tarik(double jumlah) {
        if (jumlah <= 0) {
            throw new IllegalArgumentException("Jumlah penarikan harus lebih dari 0");
        }
        if (jumlah > BATAS_PENARIKAN_SEKALI) {
            throw new IllegalArgumentException("Penarikan melebihi batas transaksi");
        }
        if (jumlah > saldo) {
            throw new IllegalArgumentException("Saldo tidak cukup");
        }

        saldo -= jumlah;
    }

    /** Kurangi saldo sebesar biaya administrasi, saldo tidak boleh jadi negatif. */
    public void potongBiayaAdmin() {
        saldo = Math.max(0, saldo - BIAYA_ADMINISTRASI);
    }

    /** Method statis: jumlah rekening yang pernah dibuat. */
    public static int getJumlahRekening() {
        return jumlahRekening;
    }

    /** Method statis utilitas: tidak membaca keadaan objek mana pun. */
    public static double bungaSetahun(double pokok) {
        return pokok * BUNGA_TAHUNAN;
    }

    public double getSaldo() { return saldo; }
    public String getNomor() { return nomor; }

    @Override
    public String toString() {
        return String.format("Rekening[%s] %-14s Rp%,.2f", nomor, pemilik, saldo);
    }
}
