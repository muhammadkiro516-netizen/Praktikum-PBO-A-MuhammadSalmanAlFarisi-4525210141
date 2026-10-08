/**
 * Enum: hanya nilai yang terdaftar di sini yang mungkin ada.
 * Bandingkan dengan `public static final int BENSIN = 1;`
 * yang membiarkan angka 99 lolos begitu saja.
 */
public enum TipeBahanBakar {

    // TODO 1 (selesai): konstanta enum beserta label dan harga per satuannya.
    // TODO 2 (selesai): LISTRIK ditambahkan (harga per kWh).
    BENSIN("Bensin", 12_000),
    SOLAR("Solar", 10_500),
    LISTRIK("Listrik", 2_500);

    private final String label;
    private final double hargaPerSatuan;

    TipeBahanBakar(String label, double hargaPerSatuan) {
        this.label = label;
        this.hargaPerSatuan = hargaPerSatuan;
    }

    public String getLabel() { return label; }

    public double getHargaPerSatuan() { return hargaPerSatuan; }

    /** TODO 3 (selesai): enum boleh punya method, hal yang tidak bisa dilakukan konstanta int. */
    public double biayaPengisian(double jumlah) {
        if (jumlah < 0) {
            throw new IllegalArgumentException("Jumlah pengisian tidak boleh negatif.");
        }
        return jumlah * hargaPerSatuan;
    }

    /** TODO 4 (selesai): true hanya untuk LISTRIK. */
    public boolean ramahLingkungan() {
        return this == LISTRIK;
    }
}
