<?php
declare(strict_types=1);

/**
 * Sesi 2 — enkapsulasi yang menjaga invariant (PHP).
 * Bandingkan baris demi baris dengan java/Mahasiswa.java.
 */
class Mahasiswa
{
    public const BOBOT_TUGAS = 0.30;
    public const BOBOT_UTS   = 0.30;
    public const BOBOT_UAS   = 0.40;

    private const NILAI_MIN = 0;
    private const NILAI_MAX = 100;

    private readonly string $nim;
    private readonly string $nama;
    private float $nilaiTugas;
    private float $nilaiUts;
    private float $nilaiUas;

    public function __construct(
        string $nim,
        string $nama,
        float $nilaiTugas,
        float $nilaiUts,
        float $nilaiUas,
    ) {
        $nim = trim($nim);
        if ($nim === '') {
            throw new InvalidArgumentException('NIM tidak boleh kosong atau null.');
        }

        $this->nim = $nim;
        $this->nama = $nama;
        $this->nilaiTugas = $nilaiTugas;
        $this->nilaiUts = $nilaiUts;
        $this->nilaiUas = $nilaiUas;

        self::pastikanNilaiSah('nilai tugas', $this->nilaiTugas);
        self::pastikanNilaiSah('nilai UTS', $this->nilaiUts);
        self::pastikanNilaiSah('nilai UAS', $this->nilaiUas);
    }

    /**
     * TODO 4: lengkapi validasi satu komponen nilai.
     */
    private static function pastikanNilaiSah(string $namaKomponen, float $nilai): void
    {
        if ($nilai < self::NILAI_MIN || $nilai > self::NILAI_MAX) {
            throw new InvalidArgumentException(
                $namaKomponen . ' harus berada dalam rentang 0 sampai 100.'
            );
        }
    }

    /** TODO 5: hitung nilai akhir memakai konstanta bobot. */
    public function nilaiAkhir(): float
    {
        return ($this->nilaiTugas * self::BOBOT_TUGAS)
             + ($this->nilaiUts * self::BOBOT_UTS)
             + ($this->nilaiUas * self::BOBOT_UAS);
    }

    /** TODO 6: kembalikan huruf mutu. Petunjuk: match (true) { ... } */
    public function hurufMutu(): string
    {
        return match (true) {
            $this->nilaiAkhir() >= 80 => 'A',
            $this->nilaiAkhir() >= 70 => 'B',
            $this->nilaiAkhir() >= 60 => 'C',
            $this->nilaiAkhir() >= 50 => 'D',
            default => 'E',
        };
    }

    // TODO 7: sediakan getter seperlunya. JANGAN membuat setNim().
    public function getNim(): string  { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getNilaiAkhir(): float { return $this->nilaiAkhir(); }

    public function __toString(): string
    {
        return sprintf('%-10s %-18s akhir=%6.2f  mutu=%s',
            $this->nim, $this->nama, $this->nilaiAkhir(), $this->hurufMutu());
    }
}
