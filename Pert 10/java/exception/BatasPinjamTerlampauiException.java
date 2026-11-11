package exception;

public class BatasPinjamTerlampauiException extends PerpustakaanException {
    private final String idAnggota;
    private final int batas;
    public BatasPinjamTerlampauiException(String idAnggota, int batas) {
        super("Anggota " + idAnggota + " telah mencapai batas pinjam maksimum (" + batas + " koleksi).");
        this.idAnggota = idAnggota;
        this.batas = batas;
    }
    public String getIdAnggota() { return idAnggota; }
    public int getBatas() { return batas; }
}
