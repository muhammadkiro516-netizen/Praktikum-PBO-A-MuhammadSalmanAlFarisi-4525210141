package id.ac.univpancasila.perpustakaan;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;

import java.util.List;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Sesi 11 — unit test pertama.
 * Letak: src/test/java/id/ac/univpancasila/perpustakaan/. Jalankan: mvn test
 *
 * Konvensi Maven: hanya berkas di src/test/java yang bernama *Test
 * yang dijalankan sebagai pengujian.
 */
class RepositoryTest {

    /** Objek sederhana untuk diuji. */
    record Buku(String isbn, String judul) {}

    private Repository<Buku> repo;

    @BeforeEach
    void siapkan() {
        // Dijalankan ulang sebelum SETIAP pengujian, sehingga tiap uji
        // berangkat dari keadaan yang bersih dan tidak saling memengaruhi.
        repo = new Repository<>(Buku::isbn);
    }

    @Test
    @DisplayName("Item yang disimpan dapat ditemukan kembali")
    void simpanLaluTemukan() {
        repo.simpan(new Buku("978-1", "PBO"));

        assertTrue(repo.cariById("978-1").isPresent());
        assertEquals("PBO", repo.cariById("978-1").get().judul());
    }

    @Test
    @DisplayName("Mencari id yang tidak ada mengembalikan Optional kosong")
    void cariYangTidakAda() {
        assertTrue(repo.cariById("999").isEmpty());
    }

    @Test
    @DisplayName("Item yang dihapus tidak dapat ditemukan lagi")
    void hapusItem() {
        repo.simpan(new Buku("978-1", "PBO"));

        assertTrue(repo.hapus("978-1"));
        assertTrue(repo.cariById("978-1").isEmpty());
    }

    @Test
    @DisplayName("Repository baru berisi nol item")
    void repositoryKosong() {
        assertEquals(0, repo.jumlah());
        assertTrue(repo.cariSemua().isEmpty());
    }

    @Test
    @DisplayName("Koleksi yang dikembalikan cariSemua tidak dapat diubah dari luar")
    void koleksiTidakBocor() {
        repo.simpan(new Buku("978-1", "PBO"));

        List<Buku> salinan = repo.cariSemua();
        assertThrows(UnsupportedOperationException.class, () -> salinan.add(new Buku("978-2", "Lain")));
        assertEquals(1, repo.jumlah());
    }

    @Test
    @DisplayName("Menyimpan dengan kunci yang sama menimpa item lama")
    void kunciSamaMenimpa() {
        repo.simpan(new Buku("978-1", "Judul Lama"));
        repo.simpan(new Buku("978-1", "Judul Baru"));

        assertEquals(1, repo.jumlah());
        assertEquals("Judul Baru", repo.cariById("978-1").get().judul());
    }

    @Test
    @DisplayName("Menghapus id yang tidak ada mengembalikan false")
    void hapusYangTidakAda() {
        assertFalse(repo.hapus("999"));
    }

    @Test
    @DisplayName("Menyimpan null ditolak")
    void simpanNullDitolak() {
        assertThrows(NullPointerException.class, () -> repo.simpan(null));
    }
}
