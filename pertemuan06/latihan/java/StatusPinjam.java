/** Enum dengan perilaku: setiap status tahu keterangannya sendiri. */
public enum StatusPinjam {
    TERSEDIA,
    DIPINJAM,
    TERLAMBAT,
    HILANG;

    public String keterangan() {
        return switch (this) {
            case TERSEDIA  -> "Ada di rak dan bisa dipinjam";
            case DIPINJAM  -> "Sedang dipinjam anggota";
            case TERLAMBAT -> "Lewat tenggat, denda berjalan";
            case HILANG    -> "Tidak ditemukan, perlu penggantian";
        };
    }
}
