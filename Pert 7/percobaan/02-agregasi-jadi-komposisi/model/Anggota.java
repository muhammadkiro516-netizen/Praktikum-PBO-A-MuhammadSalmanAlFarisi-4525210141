package model;

/**
 * Anggota perpustakaan. Nomor anggota tidak pernah berubah setelah
 * didaftarkan: field final dan tidak ada setter.
 */
public class Anggota {

    private final String nomorAnggota;
    private final String nama;
    private final JenisKeanggotaan jenis;

    public Anggota(String nomorAnggota, String nama, JenisKeanggotaan jenis) {
        if (nomorAnggota == null || nomorAnggota.isBlank()) {
            throw new IllegalArgumentException("Nomor anggota tidak boleh kosong");
        }
        if (nama == null || nama.isBlank()) {
            throw new IllegalArgumentException("Nama tidak boleh kosong");
        }
        if (jenis == null) {
            throw new IllegalArgumentException("Jenis keanggotaan tidak boleh null");
        }
        this.nomorAnggota = nomorAnggota;
        this.nama = nama;
        this.jenis = jenis;
    }

    public String getNomorAnggota()   { return nomorAnggota; }
    public String getNama()           { return nama; }
    public JenisKeanggotaan getJenis() { return jenis; }

    @Override
    public String toString() {
        return String.format("%s %s (%s)", nomorAnggota, nama, jenis.nama());
    }
}
