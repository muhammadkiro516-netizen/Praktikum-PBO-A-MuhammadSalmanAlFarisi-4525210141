package id.ac.univpancasila.perpustakaan.service;

import id.ac.univpancasila.perpustakaan.exception.AnggotaTidakDitemukanException;
import id.ac.univpancasila.perpustakaan.exception.BatasPinjamTerlampauiException;
import id.ac.univpancasila.perpustakaan.exception.BukuSedangDipinjamException;
import id.ac.univpancasila.perpustakaan.exception.BukuTidakDitemukanException;
import id.ac.univpancasila.perpustakaan.model.Buku;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Skenario kegagalan dari skenario-uji.md (sesi 10) yang diubah menjadi
 * kasus uji otomatis. Nomor skenario disebut pada nama tampilan tiap uji.
 */
class LibraryServiceTest {

    private LibraryService layanan;

    @BeforeEach
    void siapkan() {
        layanan = new LibraryService();
        layanan.tambahBuku(new Buku("978-1", "Basis Data", "R. Elmasri", 2016, 1));
        layanan.tambahBuku(new Buku("978-2", "PBO", "A. W. Pribadi", 2020, 5));
        layanan.daftarkanAnggota("A-001");
        layanan.daftarkanAnggota("A-002");
    }

    @Test
    @DisplayName("Skenario 1: ISBN tidak terdaftar melempar BukuTidakDitemukanException")
    void isbnTidakTerdaftar() {
        BukuTidakDitemukanException e = assertThrows(BukuTidakDitemukanException.class,
                () -> layanan.pinjam("A-001", "978-9"));

        assertEquals("978-9", e.getIsbn());
        assertEquals("Buku dengan ISBN 978-9 tidak ditemukan", e.getMessage());
    }

    @Test
    @DisplayName("Skenario 2: anggota tidak terdaftar melempar AnggotaTidakDitemukanException")
    void anggotaTidakTerdaftar() {
        AnggotaTidakDitemukanException e = assertThrows(AnggotaTidakDitemukanException.class,
                () -> layanan.pinjam("A-009", "978-1"));

        assertEquals("A-009", e.getIdAnggota());
    }

    @Test
    @DisplayName("Skenario 3: anggota diperiksa lebih dulu bila ID dan ISBN sama-sama salah")
    void urutanPemeriksaan() {
        assertThrows(AnggotaTidakDitemukanException.class, () -> layanan.pinjam("A-009", "978-9"));
    }

    @Test
    @DisplayName("Skenario 4: stok habis melempar BukuSedangDipinjamException")
    void stokHabis() {
        layanan.pinjam("A-001", "978-1");   // satu-satunya eksemplar

        BukuSedangDipinjamException e = assertThrows(BukuSedangDipinjamException.class,
                () -> layanan.pinjam("A-002", "978-1"));

        assertEquals("978-1", e.getIsbn());
        assertEquals("Basis Data", e.getJudul());
    }

    @Test
    @DisplayName("Skenario 5: pinjaman ke-4 melempar BatasPinjamTerlampauiException")
    void batasPinjam() {
        for (int i = 0; i < LibraryService.BATAS_PINJAM; i++) layanan.pinjam("A-001", "978-2");

        BatasPinjamTerlampauiException e = assertThrows(BatasPinjamTerlampauiException.class,
                () -> layanan.pinjam("A-001", "978-2"));

        assertEquals("A-001", e.getIdAnggota());
        assertEquals(3, e.getBatas());
    }

    @Test
    @DisplayName("Pinjam yang berhasil mengurangi stok dan menambah hitungan pinjaman")
    void pinjamBerhasil() {
        layanan.pinjam("A-001", "978-2");

        assertEquals(4, layanan.cariBuku("978-2").get().getJumlahEksemplar());
        assertEquals(1, layanan.jumlahPinjaman("A-001"));
    }

    @Test
    @DisplayName("Pinjam yang gagal tidak mengubah stok")
    void gagalTidakMengubahStok() {
        assertThrows(AnggotaTidakDitemukanException.class, () -> layanan.pinjam("A-009", "978-2"));

        assertEquals(5, layanan.cariBuku("978-2").get().getJumlahEksemplar());
    }

    @Test
    @DisplayName("cariBuku mengembalikan Optional kosong, bukan null")
    void cariBukuTidakAda() {
        assertTrue(layanan.cariBuku("999").isEmpty());
    }
}
