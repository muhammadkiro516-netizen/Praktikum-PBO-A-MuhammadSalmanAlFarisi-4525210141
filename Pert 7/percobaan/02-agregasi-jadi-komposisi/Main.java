import service.PerpustakaanService;

/** Latihan mandiri E1: relasi Service-Koleksi diubah dari agregasi menjadi komposisi. */
public class Main {
    public static void main(String[] args) {
        PerpustakaanService layanan = new PerpustakaanService();
        layanan.tambahMajalah("MJ-014", "Majalah Informatika", "Redaksi", 2026, 2);
        System.out.println("Katalog: " + layanan.getKoleksi().size() + " koleksi");

        layanan.hapusKoleksi("MJ-014");
        System.out.println("Setelah dihapus: " + layanan.getKoleksi().size() + " koleksi");
        System.out.println("Objek Majalah tidak punya jalan keluar untuk tetap hidup di luar: hapusKoleksi() kini void.");
    }
}
