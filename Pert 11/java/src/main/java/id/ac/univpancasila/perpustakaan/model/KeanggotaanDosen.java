package id.ac.univpancasila.perpustakaan.model;

public final class KeanggotaanDosen implements JenisKeanggotaan {
    @Override public String nama()      { return "Dosen"; }
    @Override public int dendaPerHari() { return 500; }
}
