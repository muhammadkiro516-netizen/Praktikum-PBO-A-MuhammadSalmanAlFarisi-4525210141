package model;

public class Dosen {
    private final String nip;
    private final String nama;

    public Dosen(String nip, String nama) {
        if (nip == null || nip.isBlank() || nama == null || nama.isBlank()) {
            throw new IllegalArgumentException("NIP dan nama dosen wajib diisi");
        }
        this.nip = nip;
        this.nama = nama;
    }

    public String getNip()  { return nip; }
    public String getNama() { return nama; }

    @Override public String toString() { return nama + " (" + nip + ")"; }
}
