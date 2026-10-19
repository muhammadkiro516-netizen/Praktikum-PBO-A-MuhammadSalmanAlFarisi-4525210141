package model;

/** Status kehadiran seorang mahasiswa pada satu pertemuan. */
public enum StatusKehadiran {
    HADIR("Hadir", true),
    IZIN("Izin", false),
    SAKIT("Sakit", false),
    ALPA("Alpa", false);

    private final String label;
    private final boolean dihitungHadir;

    StatusKehadiran(String label, boolean dihitungHadir) {
        this.label = label;
        this.dihitungHadir = dihitungHadir;
    }

    /** Hanya HADIR yang menambah persentase kehadiran. */
    public boolean dihitungHadir() { return dihitungHadir; }
    public String getLabel()       { return label; }
}
