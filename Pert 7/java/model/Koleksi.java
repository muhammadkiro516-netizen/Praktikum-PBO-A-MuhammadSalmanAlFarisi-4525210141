package model;

/**
 * Induk semua koleksi perpustakaan. Aturan yang berbeda antarjenis
 * (masa pinjam dan boleh-tidaknya dipinjam) dibiarkan abstrak, sehingga
 * jenis koleksi baru cukup menambah turunan tanpa menyunting kelas lain.
 */
public abstract class Koleksi {

    private final String nomorInduk;
    private final String judul;
    private final String penulis;
    private final int tahun;
    private int jumlahEksemplar;
    private StatusKoleksi status = StatusKoleksi.TERSEDIA;

    protected Koleksi(String nomorInduk, String judul, String penulis, int tahun, int jumlahEksemplar) {
        if (nomorInduk == null || nomorInduk.isBlank()) {
            throw new IllegalArgumentException("Nomor induk tidak boleh kosong");
        }
        if (judul == null || judul.isBlank()) {
            throw new IllegalArgumentException("Judul tidak boleh kosong");
        }
        if (penulis == null || penulis.isBlank()) {
            throw new IllegalArgumentException("Penulis tidak boleh kosong");
        }
        if (tahun <= 0) {
            throw new IllegalArgumentException("Tahun terbit harus positif: " + tahun);
        }
        if (jumlahEksemplar < 0) {
            throw new IllegalArgumentException("Jumlah eksemplar tidak boleh negatif: " + jumlahEksemplar);
        }
        this.nomorInduk = nomorInduk;
        this.judul = judul;
        this.penulis = penulis;
        this.tahun = tahun;
        this.jumlahEksemplar = jumlahEksemplar;
        if (jumlahEksemplar == 0) {
            this.status = StatusKoleksi.DIPINJAM;
        }
    }

    /** Masa pinjam dalam hari; tiap jenis koleksi punya aturannya sendiri. */
    public abstract int masaPinjamHari();

    /** Apakah JENIS koleksi ini pada dasarnya boleh dipinjam (skripsi: tidak). */
    public abstract boolean bolehDipinjam();

    public boolean tersedia() { return jumlahEksemplar > 0; }

    /**
     * Koleksi sendiri yang memutuskan dapat-tidaknya ia dipinjam, karena
     * semua data penentunya (jenis, status, stok) ada di sini.
     */
    public boolean dapatDipinjam() {
        return bolehDipinjam() && status.membolehkanPeminjaman() && tersedia();
    }

    /** Alasan penolakan untuk pesan galat; null bila koleksi dapat dipinjam. */
    public String alasanTidakDapatDipinjam() {
        if (!bolehDipinjam()) return "hanya boleh dibaca di tempat";
        if (!status.membolehkanPeminjaman()) return "berstatus " + status.getLabel().toLowerCase();
        if (!tersedia()) return "tidak ada eksemplar tersedia";
        return null;
    }

    /** Kurangi stok satu eksemplar; tolak bila sudah nol. */
    public void kurangiStok() {
        if (jumlahEksemplar == 0) {
            throw new IllegalStateException("Eksemplar '" + judul + "' sudah habis");
        }
        jumlahEksemplar--;
        if (jumlahEksemplar == 0 && status == StatusKoleksi.TERSEDIA) {
            status = StatusKoleksi.DIPINJAM;
        }
    }

    public void tambahStok() {
        jumlahEksemplar++;
        if (status == StatusKoleksi.DIPINJAM) {
            status = StatusKoleksi.TERSEDIA;
        }
    }

    public void tandaiRusak()  { status = StatusKoleksi.RUSAK; }
    public void tandaiHilang() { status = StatusKoleksi.HILANG; }

    public String getNomorInduk()    { return nomorInduk; }
    public String getJudul()         { return judul; }
    public String getPenulis()       { return penulis; }
    public int getTahun()            { return tahun; }
    public int getJumlahEksemplar()  { return jumlahEksemplar; }
    public StatusKoleksi getStatus() { return status; }

    @Override
    public String toString() {
        return String.format("%-8s %-38s %-14s %d [%d eks, %s]",
                nomorInduk, judul, penulis, tahun, jumlahEksemplar, status.getLabel());
    }
}
