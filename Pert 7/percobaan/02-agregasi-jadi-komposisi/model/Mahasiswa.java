package model;

public final class Mahasiswa implements JenisKeanggotaan {
    @Override public String nama()       { return "Mahasiswa"; }
    @Override public int dendaPerHari()  { return 1_000; }
}
