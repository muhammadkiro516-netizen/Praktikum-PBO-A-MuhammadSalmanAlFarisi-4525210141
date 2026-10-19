<?php

require_once 'Bab.php';

class Buku
{
    private $judulBuku;
    private $daftarBab;

    // komposisi: objek Bab dibuat di dalam class Buku,
    // jadi kalau Buku hilang, Bab-nya ikut hilang
    public function __construct($judulBuku)
    {
        $this->judulBuku = $judulBuku;
        $this->daftarBab = [];
        $this->tambahBab();
    }

    private function tambahBab()
    {
        $this->daftarBab[] = new Bab("Pendahuluan");
        $this->daftarBab[] = new Bab("Isi");
        $this->daftarBab[] = new Bab("Penutup");
    }

    public function tampilkanBab()
    {
        echo "Buku " . $this->judulBuku . " memiliki bab:" . PHP_EOL;
        foreach ($this->daftarBab as $bab) {
            echo "- " . $bab->getJudulBab() . PHP_EOL;
        }
    }
}
