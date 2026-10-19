/** Printer mengambil dua kontrak kecil yang relevan, bukan delapan. */
public class Printer implements Switchable, Printable {
    @Override public void hidupkan() { System.out.println("Printer siap"); }
    @Override public void matikan()  { System.out.println("Printer mati"); }
    @Override public void cetak(String teks) { System.out.println("Mencetak: " + teks); }
}
