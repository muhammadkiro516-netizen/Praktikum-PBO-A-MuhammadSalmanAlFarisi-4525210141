package id.ac.univpancasila.perpustakaan.service;

import id.ac.univpancasila.perpustakaan.Repository;
import id.ac.univpancasila.perpustakaan.exception.AnggotaTidakDitemukanException;
import id.ac.univpancasila.perpustakaan.exception.BatasPinjamTerlampauiException;
import id.ac.univpancasila.perpustakaan.exception.BukuSedangDipinjamException;
import id.ac.univpancasila.perpustakaan.exception.BukuTidakDitemukanException;
import id.ac.univpancasila.perpustakaan.model.Buku;

import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

/**
 * LibraryService (guard clause dari sesi 10) yang struktur datanya diganti
 * dengan Collections Framework pada sesi 11:
 *
 *   - Repository<Buku>      : buku dicari berdasarkan ISBN lewat Map, bukan perulangan.
 *   - Map<String, Integer>  : jumlah pinjaman per anggota (kunci = ID anggota).
 *
 * Tiga Map paralel pada versi sebelumnya (stok, judul, pinjaman) kini tinggal
 * satu: stok dan judul menjadi milik objek Buku itu sendiri.
 * Alasan pemilihan struktur ada di pilihan-koleksi.md.
 */
public class LibraryService {

    public static final int BATAS_PINJAM = 3;

    private final Repository<Buku> bukuRepo = new Repository<>(Buku::getNomorInduk);
    private final Map<String, Integer> pinjaman = new LinkedHashMap<>();

    public void tambahBuku(Buku buku) {
        bukuRepo.simpan(buku);
    }

    public void daftarkanAnggota(String idAnggota) {
        if (idAnggota == null || idAnggota.isBlank())
            throw new IllegalArgumentException("ID anggota tidak boleh kosong.");
        pinjaman.putIfAbsent(idAnggota, 0);
    }

    /** Pencarian mengembalikan Optional, bukan null. */
    public Optional<Buku> cariBuku(String isbn) {
        return bukuRepo.cariById(isbn);
    }

    public List<Buku> semuaBuku() {
        return bukuRepo.cariSemua();
    }

    public int jumlahPinjaman(String idAnggota) {
        return pinjaman.getOrDefault(idAnggota, 0);
    }

    /**
     * Meminjam satu eksemplar. Guard clause: setiap pemeriksaan diakhiri throw.
     *
     * @throws AnggotaTidakDitemukanException bila anggota belum terdaftar
     * @throws BukuTidakDitemukanException    bila ISBN tidak terdaftar
     * @throws BukuSedangDipinjamException    bila semua eksemplar sedang dipinjam
     * @throws BatasPinjamTerlampauiException bila anggota sudah mencapai batas
     */
    public void pinjam(String idAnggota, String isbn) {
        if (!pinjaman.containsKey(idAnggota))
            throw new AnggotaTidakDitemukanException(idAnggota);
        Buku buku = bukuRepo.cariById(isbn)
                .orElseThrow(() -> new BukuTidakDitemukanException(isbn));
        if (buku.getJumlahEksemplar() <= 0)
            throw new BukuSedangDipinjamException(isbn, buku.getJudul());
        if (pinjaman.get(idAnggota) >= BATAS_PINJAM)
            throw new BatasPinjamTerlampauiException(idAnggota, BATAS_PINJAM);

        buku.kurangiStok();
        pinjaman.merge(idAnggota, 1, Integer::sum);
    }
}
