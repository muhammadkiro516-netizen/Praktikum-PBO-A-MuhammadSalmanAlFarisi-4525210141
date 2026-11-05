package model;

/**
 * Induk semua koleksi perpustakaan: buku, majalah, dan skripsi.
 * Data yang sama pada semua koleksi ditulis sekali di sini;
 * aturan masa pinjam dan boleh-tidaknya dipinjam diserahkan ke turunan.
 */
public abstract class Koleksi {

    private final String nomorInduk;
    private final String judul;
    private final String penulis;
    private final int tahun;
    private int jumlahEksemplar;
    private StatusKoleksi status = StatusKoleksi.TERSEDIA;

    protected Koleksi(String nomorInduk, String judul, String penulis, int tahun, int jumlahEksemplar) {
        if (nomorInduk == null || nomorInduk.isBlank())
            throw new IllegalArgumentException("Nomor induk tidak boleh kosong.");
        if (judul == null || judul.isBlank())
            throw new IllegalArgumentException("Judul tidak boleh kosong.");
        if (tahun <= 0)
            throw new IllegalArgumentException("Tahun terbit harus lebih besar dari 0.");
        // Jumlah eksemplar tidak boleh negatif.
        if (jumlahEksemplar < 0)
            throw new IllegalArgumentException("Jumlah eksemplar tidak boleh negatif: " + jumlahEksemplar);
        this.nomorInduk = nomorInduk;
        this.judul = judul;
        this.penulis = penulis;
        this.tahun = tahun;
        this.jumlahEksemplar = jumlahEksemplar;
    }

    /** Masa pinjam dalam hari. Diisi oleh setiap jenis koleksi. */
    public abstract int masaPinjamHari();

    /** Apakah jenis koleksi ini boleh dipinjam sama sekali. */
    public abstract boolean bolehDipinjam();

    /** Tersedia bila statusnya mengizinkan dan masih ada eksemplar. */
    public boolean tersedia() {
        return status.bisaDipinjam() && jumlahEksemplar > 0;
    }

    /** Kurangi stok; tolak bila sudah nol. */
    public void kurangiStok() {
        if (jumlahEksemplar == 0)
            throw new IllegalStateException("Tidak ada eksemplar tersedia untuk " + nomorInduk + ".");
        jumlahEksemplar--;
        if (jumlahEksemplar == 0 && status == StatusKoleksi.TERSEDIA)
            status = StatusKoleksi.DIPINJAM;
    }

    public void tambahStok() {
        jumlahEksemplar++;
        if (status == StatusKoleksi.DIPINJAM)
            status = StatusKoleksi.TERSEDIA;
    }

    public void tandaiRusak()  { status = StatusKoleksi.RUSAK; }
    public void tandaiHilang() { status = StatusKoleksi.HILANG; }

    public String getNomorInduk()    { return nomorInduk; }
    public String getJudul()         { return judul; }
    public String getPenulis()       { return penulis; }
    public int getTahun()            { return tahun; }
    public int getJumlahEksemplar()  { return jumlahEksemplar; }
    public StatusKoleksi getStatus() { return status; }
}
