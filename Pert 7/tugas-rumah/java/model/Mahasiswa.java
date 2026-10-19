package model;

/** Mahasiswa hidup sendiri; kelas hanya merujuknya (agregasi) dan presensi mengaitkannya (asosiasi). */
public class Mahasiswa {
    private final String npm;
    private final String nama;

    public Mahasiswa(String npm, String nama) {
        if (npm == null || npm.isBlank() || nama == null || nama.isBlank()) {
            throw new IllegalArgumentException("NPM dan nama mahasiswa wajib diisi");
        }
        this.npm = npm;
        this.nama = nama;
    }

    public String getNpm()  { return npm; }
    public String getNama() { return nama; }

    @Override public String toString() { return npm + " " + nama; }
}
