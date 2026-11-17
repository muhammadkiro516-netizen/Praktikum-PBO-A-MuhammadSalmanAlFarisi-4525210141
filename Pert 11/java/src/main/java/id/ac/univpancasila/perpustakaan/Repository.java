package id.ac.univpancasila.perpustakaan;

import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.Objects;
import java.util.Optional;
import java.util.function.Function;

/**
 * Sesi 11 — kelas generik Anda sendiri.
 *
 * <T> adalah TYPE PARAMETER: kelas ini bekerja untuk tipe apa pun, tetapi
 * setelah dipakai sebagai Repository<Buku>, kompilator memastikan hanya Buku
 * yang bisa masuk dan keluar.
 *
 * Bandingkan tanda tangannya dengan JpaRepository<Buku, Long> yang akan Anda
 * temui di Spring Data JPA pada sesi 14. Gagasannya persis sama.
 */
public class Repository<T> {

    // Map dipilih (bukan List) agar pencarian berdasarkan kunci langsung, tanpa
    // perulangan linear. LinkedHashMap menjaga urutan penyimpanan.
    private final Map<String, T> data = new LinkedHashMap<>();

    /**
     * Fungsi untuk mengambil kunci dari sebuah item.
     * Dengan cara ini Repository tidak perlu tahu apa pun tentang T.
     */
    private final Function<T, String> pengambilKunci;

    public Repository(Function<T, String> pengambilKunci) {
        this.pengambilKunci = Objects.requireNonNull(pengambilKunci, "pengambilKunci tidak boleh null");
    }

    /**
     * Menyimpan item dengan kunci dari pengambilKunci. Bila kunci yang sama
     * sudah ada, item lama DITIMPA (rancangan ini dipilih agar simpan() juga
     * berfungsi sebagai pembaruan).
     */
    public void simpan(T item) {
        Objects.requireNonNull(item, "item tidak boleh null");
        data.put(pengambilKunci.apply(item), item);
    }

    /** Optional berisi item bila ada, kosong bila tidak. Tidak pernah null. */
    public Optional<T> cariById(String id) {
        return Optional.ofNullable(data.get(id));
    }

    /** Salinan TAK-BERUBAH dari seluruh isi: koleksi internal tidak bocor. */
    public List<T> cariSemua() {
        return List.copyOf(data.values());
    }

    /** Menghapus item; true bila ada yang terhapus. */
    public boolean hapus(String id) {
        return data.remove(id) != null;
    }

    public int jumlah() { return data.size(); }
}
