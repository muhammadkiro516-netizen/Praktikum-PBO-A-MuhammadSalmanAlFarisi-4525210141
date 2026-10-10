/** Lampu hanya butuh hidupkan() dan matikan(), tetapi dipaksa menerima delapan method. */
public class Lampu implements PerangkatSerbaBisa {
    @Override public void hidupkan() { System.out.println("Lampu menyala"); }
    @Override public void matikan()  { System.out.println("Lampu padam"); }
}
