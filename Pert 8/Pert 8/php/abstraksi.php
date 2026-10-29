<?php
declare(strict_types=1);

// ══ INTERFACE: kontrak "apa yang bisa dilakukan" ═══════════════
interface Movable
{
    public function bergerak(): void;
    public function kecepatanMaksimum(): float;
}

interface Fuelable
{
    public function isiBahanBakar(float $jumlah): void;
    public function kapasitasTangki(): float;
    public function tipeBahanBakar(): TipeBahanBakar;
}

// ══ ENUM (PHP 8.1+): backed enum, punya nilai string ══════════
enum TipeBahanBakar: string
{
    case Bensin  = 'bensin';
    case Solar   = 'solar';
    case Listrik = 'listrik';   // TODO 1 (selesai)

    /** TODO 2 (selesai): label yang enak dibaca. */
    public function label(): string
    {
        return match ($this) {
            self::Bensin  => 'Bensin',
            self::Solar   => 'Solar',
            self::Listrik => 'Listrik',
        };
    }

    /** TODO 3 (selesai): Bensin 12000, Solar 10500, Listrik 2500. */
    public function hargaPerSatuan(): float
    {
        return match ($this) {
            self::Bensin  => 12_000,
            self::Solar   => 10_500,
            self::Listrik => 2_500,
        };
    }

    /** TODO 4 (selesai). */
    public function biayaPengisian(float $jumlah): float
    {
        if ($jumlah < 0) {
            throw new InvalidArgumentException('Jumlah pengisian tidak boleh negatif.');
        }
        return $jumlah * $this->hargaPerSatuan();
    }

    /** TODO 5 (selesai): hanya Listrik yang ramah lingkungan. */
    public function ramahLingkungan(): bool
    {
        return $this === self::Listrik;
    }
}

// ══ TRAIT: penggunaan ulang horizontal, khas PHP ══════════════
trait Loggable
{
    /**
     * TODO 6 (selesai): cetak baris log berformat
     *   [14:32:05] Mobil: servis berkala selesai
     * static::class memberi nama kelas yang memakai trait ini.
     */
    public function log(string $pesan): void
    {
        printf('  [%s] %s: %s%s', date('H:i:s'), static::class, $pesan, PHP_EOL);
    }
}

// ══ ABSTRACT CLASS: kode yang benar-benar sama ════════════════
abstract class Kendaraan
{
    public function __construct(
        protected readonly string $merek,
        protected readonly int    $tahun,
    ) {
        if (trim($merek) === '') {
            throw new InvalidArgumentException('Merek tidak boleh kosong.');
        }
        if ($tahun <= 0) {
            throw new InvalidArgumentException('Tahun harus lebih besar dari 0.');
        }
    }

    /** TODO 7 (selesai): umur kendaraan, tidak boleh negatif. */
    public function umur(int $tahunSekarang): int
    {
        return max(0, $tahunSekarang - $this->tahun);
    }

    abstract public function jumlahRoda(): int;

    public function getMerek(): string { return $this->merek; }
    public function getTahun(): int    { return $this->tahun; }

    public function __toString(): string
    {
        return sprintf('%s (%d, %d roda)', $this->merek, $this->tahun, $this->jumlahRoda());
    }
}

final class Mobil extends Kendaraan implements Movable, Fuelable
{
    use Loggable;                       // trait disisipkan

    private float $isiTangki = 0.0;

    public function __construct(string $merek, int $tahun, private readonly float $kapasitas)
    {
        parent::__construct($merek, $tahun);
        if ($kapasitas <= 0) {
            throw new InvalidArgumentException('Kapasitas tangki harus lebih besar dari 0.');
        }
    }

    public function jumlahRoda(): int { return 4; }

    // TODO 8 (selesai): kontrak Movable dan Fuelable.
    public function bergerak(): void
    {
        echo '  ', $this->merek, ' melaju di jalan raya', PHP_EOL;
    }

    public function kecepatanMaksimum(): float { return 180; }

    public function isiBahanBakar(float $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah bahan bakar harus lebih besar dari 0.');
        }
        if ($this->isiTangki + $jumlah > $this->kapasitas) {
            throw new InvalidArgumentException(sprintf(
                'Melebihi kapasitas tangki (%.0f dari %.0f liter).',
                $this->isiTangki + $jumlah, $this->kapasitas
            ));
        }
        $this->isiTangki += $jumlah;
    }

    public function kapasitasTangki(): float { return $this->kapasitas; }
    public function tipeBahanBakar(): TipeBahanBakar { return TipeBahanBakar::Bensin; }
    public function getIsiTangki(): float { return $this->isiTangki; }
}

/**
 * Langkah 4: Sepeda ADALAH Kendaraan dan BISA bergerak, tetapi BUKAN Fuelable.
 * isiPenuh($sepeda) di main.php akan melempar TypeError saat dijalankan.
 */
final class Sepeda extends Kendaraan implements Movable
{
    public function jumlahRoda(): int { return 2; }

    public function bergerak(): void
    {
        echo '  ', $this->merek, ' dikayuh di jalur sepeda', PHP_EOL;
    }

    public function kecepatanMaksimum(): float { return 40; }
}

/**
 * Pertanyaan demo 3: butuh bahan bakar tetapi tidak bergerak.
 * Hanya Fuelable. Bukan Kendaraan, karena genset memang bukan kendaraan.
 * (Dinamai Genset karena Generator sudah dipakai oleh PHP sendiri.)
 */
final class Genset implements Fuelable
{
    private float $isiTangki = 0.0;

    public function __construct(private readonly string $nama, private readonly float $kapasitas)
    {
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama genset tidak boleh kosong.');
        }
        if ($kapasitas <= 0) {
            throw new InvalidArgumentException('Kapasitas tangki harus lebih besar dari 0.');
        }
    }

    public function isiBahanBakar(float $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah bahan bakar harus lebih besar dari 0.');
        }
        if ($this->isiTangki + $jumlah > $this->kapasitas) {
            throw new InvalidArgumentException('Melebihi kapasitas tangki genset.');
        }
        $this->isiTangki += $jumlah;
    }

    public function kapasitasTangki(): float { return $this->kapasitas; }
    public function tipeBahanBakar(): TipeBahanBakar { return TipeBahanBakar::Solar; }
    public function getIsiTangki(): float { return $this->isiTangki; }

    public function __toString(): string { return $this->nama . ' (genset, tidak bergerak)'; }
}

/**
 * Langkah 5: Pesanan juga memakai trait Loggable.
 * Kelas ini sama sekali bukan kerabat Kendaraan, itulah maksud
 * "penggunaan ulang horizontal".
 */
final class Pesanan
{
    use Loggable;
}
