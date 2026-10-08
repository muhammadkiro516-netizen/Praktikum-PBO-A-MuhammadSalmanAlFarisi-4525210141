/**
 * Langkah 6 - dibuat mandiri, tanpa starter.
 * Kontrak "bisa dipinjam". Ia menjawab APA YANG BISA dilakukan sebuah koleksi,
 * bukan APA koleksi itu (identitasnya ada di kelas Koleksi).
 */
public interface Peminjamable {
    boolean bolehDipinjam();
    int masaPinjamHari();
}
