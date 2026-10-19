public class UjiObjectGalat {
    public static void main(String[] args) {
        Object o = new Lingkaran(7);
        System.out.println(o.luas());          // luas() bukan milik Object
        System.out.println(o.getJariJari());   // getJariJari() juga bukan
    }
}
