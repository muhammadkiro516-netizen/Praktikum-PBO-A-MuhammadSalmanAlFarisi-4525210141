public class UjiObject {
    public static void main(String[] args) {
        Object o = new Lingkaran(7);

        // Method milik Object: boleh dipanggil.
        System.out.println("o.toString()  = " + o.toString());
        System.out.println("o.getClass()  = " + o.getClass().getName());
        System.out.println("o.hashCode() != 0 ? " + (o.hashCode() != 0));
        System.out.println("o.equals(o)   = " + o.equals(o));
    }
}
