public final class ProfilPembayaran {

    private final String bank;
    private final String nomorRekening;

    public ProfilPembayaran(String bank, String nomorRekening) {
        this.bank = bank;
        this.nomorRekening = nomorRekening;
    }

    public String getBank() { return bank; }
    public String getNomorRekening() { return nomorRekening; }
}