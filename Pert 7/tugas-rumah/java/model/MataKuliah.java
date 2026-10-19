package model;

public class MataKuliah {
    private final String kode;
    private final String nama;
    private final int sks;

    public MataKuliah(String kode, String nama, int sks) {
        if (kode == null || kode.isBlank() || nama == null || nama.isBlank()) {
            throw new IllegalArgumentException("Kode dan nama mata kuliah wajib diisi");
        }
        if (sks <= 0) throw new IllegalArgumentException("SKS harus positif: " + sks);
        this.kode = kode;
        this.nama = nama;
        this.sks = sks;
    }

    public String getKode() { return kode; }
    public String getNama() { return nama; }
    public int getSks()     { return sks; }

    @Override public String toString() { return kode + " " + nama + " (" + sks + " SKS)"; }
}
