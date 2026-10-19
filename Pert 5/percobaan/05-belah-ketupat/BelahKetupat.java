public class BelahKetupat extends BangunDatar {

    private final double diagonal1;
    private final double diagonal2;
    private final double sisi;

    public BelahKetupat(double diagonal1, double diagonal2) {
        super("BelahKetupat");
        if (diagonal1 <= 0 || diagonal2 <= 0) {
            throw new IllegalArgumentException("Diagonal harus lebih besar dari 0.");
        }
        this.diagonal1 = diagonal1;
        this.diagonal2 = diagonal2;
        // keempat sisi sama panjang: sisi = sqrt((d1/2)^2 + (d2/2)^2)
        this.sisi = Math.sqrt(Math.pow(diagonal1 / 2, 2) + Math.pow(diagonal2 / 2, 2));
    }

    @Override public double luas()     { return diagonal1 * diagonal2 / 2; }
    @Override public double keliling() { return 4 * sisi; }
}
