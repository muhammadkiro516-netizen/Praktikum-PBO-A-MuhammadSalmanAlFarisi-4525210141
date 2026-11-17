package id.ac.univpancasila.perpustakaan.util;

import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

/**
 * Sub-package util: pemformat yang dipakai bersama oleh banyak kelas.
 * Semua method statis, jadi tidak perlu membuat objek Format.
 */
public final class Format {

    private static final Locale ID = Locale.forLanguageTag("id-ID");
    private static final DateTimeFormatter TANGGAL = DateTimeFormatter.ofPattern("d MMMM yyyy", ID);

    private Format() {}

    /** 5000 menjadi "Rp5.000". */
    public static String rupiah(long nilai) {
        return String.format(ID, "Rp%,d", nilai);
    }

    /** 2026-10-15 menjadi "15 Oktober 2026". */
    public static String tanggal(LocalDate tanggal) {
        return tanggal.format(TANGGAL);
    }
}
