/** Interface gemuk: delapan method, padahal kebanyakan kelas hanya butuh dua. */
public interface PerangkatSerbaBisa {
    void hidupkan();
    void matikan();
    void cetak(String teks);
    void pindai();
    void kirimFaks(String nomor);
    void sambungkanWifi(String ssid);
    void aturVolume(int level);
    void perbaruiFirmware();
}
