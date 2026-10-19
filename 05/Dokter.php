<?php

require_once 'Pasien.php';

class Dokter
{
    private $nama;

    public function __construct($nama)
    {
        $this->nama = $nama;
    }

    // asosiasi: dokter memakai objek pasien, tapi tidak menyimpannya
    public function merawat(Pasien $pasien)
    {
        echo "Dokter " . $this->nama . " merawat pasien " . $pasien->getNama() . PHP_EOL;
    }
}
