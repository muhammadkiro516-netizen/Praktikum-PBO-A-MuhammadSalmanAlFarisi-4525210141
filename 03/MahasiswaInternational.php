<?php

require_once 'Mahasiswa.php';

// MahasiswaInternational mewarisi Mahasiswa
class MahasiswaInternational extends Mahasiswa
{
    // properti tambahan
    private $negaraAsal;

    // Urutan parameter: nama, nim, negaraAsal, umur
    // (di Java urutannya nama, nim, umur, negara. Di PHP dibalik karena
    // parameter yang punya default harus ditaruh paling belakang)
    public function __construct($nama = "Belum Diisi", $nim = "Belum Diisi", $negaraAsal = "Belum Diisi", $umur = 0)
    {
        // memanggil constructor parent
        parent::__construct($nama, $nim, $umur);
        $this->negaraAsal = $negaraAsal;
    }

    public function getNegaraAsal()
    {
        return $this->negaraAsal;
    }

    public function setNegaraAsal($negaraAsal)
    {
        $this->negaraAsal = $negaraAsal;
    }

    // override method tampilkanInfo untuk menambah info negara asal
    public function tampilkanInfo()
    {
        parent::tampilkanInfo();
        echo "Negara Asal: " . $this->negaraAsal . PHP_EOL;
    }
}
