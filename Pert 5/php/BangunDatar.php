<?php
declare(strict_types=1);

abstract class BangunDatar
{
    public function __construct(private readonly string $nama) {}

    abstract public function luas(): float;
    abstract public function keliling(): float;

    public function getNama(): string { return $this->nama; }

    public function __toString(): string
    {
        return sprintf('%-12s luas=%10.2f  keliling=%10.2f',
            $this->nama, $this->luas(), $this->keliling());
    }
}

class Lingkaran extends BangunDatar
{
    public function __construct(private readonly float $jariJari)
    {
        parent::__construct('Lingkaran');
        // TODO 1 (selesai): tolak jari-jari <= 0.
        if ($jariJari <= 0) {
            throw new InvalidArgumentException('Jari-jari harus lebih besar dari 0.');
        }
    }

    // TODO 2 (selesai): luas = pi * r^2, keliling = 2 * pi * r (memakai M_PI).
    public function luas(): float     { return M_PI * $this->jariJari ** 2; }
    public function keliling(): float { return 2 * M_PI * $this->jariJari; }

    public function getJariJari(): float { return $this->jariJari; }
}

class Persegi extends BangunDatar
{
    public function __construct(private readonly float $sisi)
    {
        parent::__construct('Persegi');
        // TODO 1 (selesai): tolak sisi <= 0.
        if ($sisi <= 0) {
            throw new InvalidArgumentException('Sisi harus lebih besar dari 0.');
        }
    }

    // TODO 2 (selesai): luas = sisi^2, keliling = 4 * sisi.
    public function luas(): float     { return $this->sisi ** 2; }
    public function keliling(): float { return 4 * $this->sisi; }
}

/**
 * Langkah 2: Segitiga dengan tiga sisi, luas memakai rumus Heron.
 * Konstruksi ditolak bila sisi tidak membentuk segitiga.
 */
class Segitiga extends BangunDatar
{
    public function __construct(
        private readonly float $a,
        private readonly float $b,
        private readonly float $c,
    ) {
        parent::__construct('Segitiga');

        if ($a <= 0 || $b <= 0 || $c <= 0) {
            throw new InvalidArgumentException('Setiap sisi harus lebih besar dari 0.');
        }
        // Jumlah dua sisi harus lebih besar dari sisi ketiga (ketiga kombinasi dicek).
        if ($a + $b <= $c || $a + $c <= $b || $b + $c <= $a) {
            throw new InvalidArgumentException(
                sprintf('Sisi %s, %s, %s tidak membentuk segitiga.', $a, $b, $c)
            );
        }
    }

    public function luas(): float
    {
        $s = ($this->a + $this->b + $this->c) / 2;
        return sqrt($s * ($s - $this->a) * ($s - $this->b) * ($s - $this->c));
    }

    public function keliling(): float { return $this->a + $this->b + $this->c; }
}

/**
 * Langkah 4: Trapesium. Hanya DITAMBAHKAN, kelas lain tidak disunting.
 */
class Trapesium extends BangunDatar
{
    public function __construct(
        private readonly float $alasAtas,
        private readonly float $alasBawah,
        private readonly float $tinggi,
        private readonly float $kakiKiri,
        private readonly float $kakiKanan,
    ) {
        parent::__construct('Trapesium');

        if ($alasAtas <= 0 || $alasBawah <= 0 || $tinggi <= 0 || $kakiKiri <= 0 || $kakiKanan <= 0) {
            throw new InvalidArgumentException('Semua ukuran trapesium harus lebih besar dari 0.');
        }
        if ($kakiKiri < $tinggi || $kakiKanan < $tinggi) {
            throw new InvalidArgumentException('Sisi miring tidak boleh lebih pendek dari tinggi.');
        }
    }

    public function luas(): float     { return ($this->alasAtas + $this->alasBawah) / 2 * $this->tinggi; }
    public function keliling(): float { return $this->alasAtas + $this->alasBawah + $this->kakiKiri + $this->kakiKanan; }
}
