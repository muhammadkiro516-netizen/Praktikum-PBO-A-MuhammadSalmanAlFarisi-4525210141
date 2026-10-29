/** Setelah dipecah, Lampu hanya mengambil kontrak yang memang ia perlukan. */
public class Lampu implements Switchable {
    @Override public void hidupkan() { System.out.println("Lampu menyala"); }
    @Override public void matikan()  { System.out.println("Lampu padam"); }
}
