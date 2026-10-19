<?php

require_once 'Pemain.php';

class Tim
{
    private $namaTim;
    private $daftarPemain;

    // agregasi: objek Pemain dibuat di luar lalu dimasukkan ke Tim,
    // jadi pemain tetap ada walaupun timnya dihapus
    public function __construct($namaTim, array $daftarPemain)
    {
        $this->namaTim = $namaTim;
        $this->daftarPemain = $daftarPemain;
    }

    public function tampilkanPemain()
    {
        echo "Tim " . $this->namaTim . " memiliki pemain:" . PHP_EOL;
        foreach ($this->daftarPemain as $pemain) {
            echo "- " . $pemain->getNama() . PHP_EOL;
        }
    }
}
