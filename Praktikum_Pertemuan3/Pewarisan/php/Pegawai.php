<?php
declare(strict_types=1);

/**
 * Sesi 4 — hierarki pegawai (PHP).
 * Seluruh hierarki ditaruh dalam satu berkas agar mudah dibaca berdampingan
 * dengan versi Java. Mulai sesi 9, satu kelas = satu berkas.
 */
final class ProfilPembayaran
{
    public function __construct(
        public readonly string $bank,
        public readonly string $nomorRekening,
    ) {}
}

abstract class Pegawai
{
    private readonly ProfilPembayaran $profilPembayaran;

    public function __construct(
        protected readonly string $nip,
        protected readonly string $nama,
        protected readonly float  $gajiPokok,
        ?ProfilPembayaran $profilPembayaran = null,
    ) {
        if ($gajiPokok < 0) {
            throw new InvalidArgumentException('Gaji pokok tidak boleh negatif.');
        }
        $this->profilPembayaran = $profilPembayaran
            ?? new ProfilPembayaran('BELUM DIATUR', '-');
    }

    /** Kembalikan gaji pokok apa adanya. */
    public function hitungGaji(): float
    {
        return $this->gajiPokok;
    }

    abstract public function jenis(): string;

    public function getNama(): string { return $this->nama; }
    public function getNip(): string  { return $this->nip; }
    public function getProfilPembayaran(): ProfilPembayaran { return $this->profilPembayaran; }

    public function __toString(): string
    {
        return sprintf('%-14s %-9s %-20s Rp%s',
            $this->nip, $this->jenis(), $this->nama,
            number_format($this->hitungGaji(), 2, ',', '.'));
    }
}

class PegawaiTetap extends Pegawai
{
    protected const TUNJANGAN_PER_TAHUN = 0.02;
    protected const TUNJANGAN_MAKSIMUM  = 0.40;

    public function __construct(
        string $nip, string $nama, float $gajiPokok,
        protected readonly int $masaKerjaTahun,
    ) {
        parent::__construct($nip, $nama, $gajiPokok);
        if ($masaKerjaTahun < 0) {
            throw new InvalidArgumentException('Masa kerja tidak boleh negatif.');
        }
    }

    /**
    * Gaji dasar induk + tunjangan masa kerja.
     *         Gunakan parent::hitungGaji(), jangan menyalin rumusnya.
     */
    public function hitungGaji(): float
    {
        $tunjangan = min($this->masaKerjaTahun * self::TUNJANGAN_PER_TAHUN,
            self::TUNJANGAN_MAKSIMUM);
        return parent::hitungGaji() * (1 + $tunjangan);
    }

    public function jenis(): string { return 'TETAP'; }
}

class PegawaiKontrak extends Pegawai
{
    public function __construct(
        string $nip, string $nama, float $gajiPokok,
        private readonly int $bulanKontrak,
    ) {
        parent::__construct($nip, $nama, $gajiPokok);
        if ($bulanKontrak < 0) {
            throw new InvalidArgumentException('Bulan kontrak tidak boleh negatif.');
        }
    }

    public function jenis(): string { return 'KONTRAK'; }

    public function getBulanKontrak(): int { return $this->bulanKontrak; }
}

class Dosen extends PegawaiTetap
{
    public function __construct(
        string $nip, string $nama, float $gajiPokok, int $masaKerjaTahun,
        private readonly float $tunjanganFungsional,
    ) {
        parent::__construct($nip, $nama, $gajiPokok, $masaKerjaTahun);
        if ($tunjanganFungsional < 0) {
            throw new InvalidArgumentException('Tunjangan fungsional tidak boleh negatif.');
        }
    }

    public function hitungGaji(): float
    {
        return parent::hitungGaji() + $this->tunjanganFungsional;
    }

    public function jenis(): string { return 'DOSEN'; }
}

class PegawaiHarian extends Pegawai
{
    public function __construct(
        string $nip, string $nama, float $tarifPerHari,
        private readonly int $jumlahHariKerja,
    ) {
        parent::__construct($nip, $nama, $tarifPerHari);
        if ($jumlahHariKerja < 0) {
            throw new InvalidArgumentException('Jumlah hari kerja tidak boleh negatif.');
        }
    }

    public function hitungGaji(): float
    {
        return parent::hitungGaji() * $this->jumlahHariKerja;
    }

    public function jenis(): string { return 'HARIAN'; }
}
