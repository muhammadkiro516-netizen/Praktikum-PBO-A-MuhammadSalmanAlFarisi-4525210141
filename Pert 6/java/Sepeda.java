/**
 * Langkah 4 - dibuat mandiri, tanpa starter.
 * Sepeda ADALAH Kendaraan dan BISA bergerak, tetapi TIDAK Fuelable.
 * Karena itu isiPenuh(sepeda) ditolak oleh kompilator (lihat percobaan/01-sepeda-ditolak).
 */
public class Sepeda extends Kendaraan implements Movable {

    private static final double KECEPATAN_MAKSIMUM = 40;

    public Sepeda(String merek, int tahun) {
        super(merek, tahun);
    }

    @Override public int jumlahRoda() { return 2; }

    @Override public void bergerak() {
        System.out.println("  " + merek + " dikayuh di jalur sepeda");
    }

    @Override public double kecepatanMaksimum() { return KECEPATAN_MAKSIMUM; }

    /**
     * Latihan mandiri E1: menimpa default method milik Movable.
     * Kecepatan sepeda bergantung pada tenaga pengayuh, jadi ringkasan bawaan
     * ("kecepatan maksimum 40 km/jam") terlalu pasti dan perlu dilengkapi.
     */
    @Override public String ringkasanGerak() {
        return Movable.super.ringkasanGerak() + " (bergantung tenaga pengayuh)";
    }
}
