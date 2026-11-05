package model;

public final class KeanggotaanMahasiswa implements JenisKeanggotaan {
    @Override public String nama()      { return "Mahasiswa"; }
    @Override public int dendaPerHari() { return 1000; }
}
