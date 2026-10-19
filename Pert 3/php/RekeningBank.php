<?php
declare(strict_types=1);

/**
 * Sesi 3 — constructor, anggota statis, dan konstanta (PHP).
 * PHP tidak punya constructor overloading; padanannya adalah default parameter
 * dan named constructor (static factory) rekeningPelajar().
 */
class RekeningBank
{
    public const BUNGA_TAHUNAN = 0.025;
    public const BIAYA_ADMINISTRASI = 5000;
    public const BATAS_PENARIKAN_SEKALI = 5000000;

    private static int $jumlahRekening = 0;

    private float $saldo;

    public function __construct(
        private readonly string $nomor,
        private readonly string $pemilik,
        float $saldoAwal = 0,
    ) {
        if (trim($this->nomor) === '') {
            throw new InvalidArgumentException('Nomor rekening tidak boleh kosong');
        }
        if ($saldoAwal < 0) {
            throw new InvalidArgumentException('Saldo awal tidak boleh negatif');
        }

        $this->saldo = $saldoAwal;
        self::$jumlahRekening++;
    }

    /** Named constructor: rekening pelajar, saldo awal nol. Memakai new static, bukan new self. */
    public static function rekeningPelajar(string $nomor, string $pemilik): static
    {
        return new static($nomor, $pemilik, 0);
    }

    public function setor(float $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah setoran harus lebih dari 0');
        }
        $this->saldo += $jumlah;
    }

    public function tarik(float $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah penarikan harus lebih dari 0');
        }
        if ($jumlah > self::BATAS_PENARIKAN_SEKALI) {
            throw new InvalidArgumentException('Penarikan melebihi batas transaksi');
        }
        if ($jumlah > $this->saldo) {
            throw new InvalidArgumentException('Saldo tidak cukup');
        }

        $this->saldo -= $jumlah;
    }

    public function potongBiayaAdmin(): void
    {
        $this->saldo = max(0, $this->saldo - self::BIAYA_ADMINISTRASI);
    }

    public static function getJumlahRekening(): int
    {
        return self::$jumlahRekening;
    }

    public static function bungaSetahun(float $pokok): float
    {
        return $pokok * self::BUNGA_TAHUNAN;
    }

    public function getSaldo(): float { return $this->saldo; }
    public function getNomor(): string { return $this->nomor; }

    public function __toString(): string
    {
        return sprintf('Rekening[%s] %-14s Rp%s',
            $this->nomor, $this->pemilik, number_format($this->saldo, 2, ',', '.'));
    }
}
