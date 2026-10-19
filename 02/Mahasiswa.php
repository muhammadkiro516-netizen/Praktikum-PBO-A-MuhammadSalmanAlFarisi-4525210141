<?php

class Mahasiswa
{
    private $nama;
    private $nim;
    private $umur;

    // PHP tidak punya constructor overloading seperti Java,
    // jadi 3 constructor di Java digabung jadi satu dengan nilai default.
    // new Mahasiswa()                   -> semua default
    // new Mahasiswa("Budi", "123")      -> umur default 0
    // new Mahasiswa("Siti", "456", 22)  -> semua diisi
    public function __construct($nama = "Belum Diisi", $nim = "Belum Diisi", $umur = 0)
    {
        $this->nama = $nama;
        $this->nim = $nim;
        $this->umur = $umur;
    }

    // Getter dan Setter
    public function getNama()
    {
        return $this->nama;
    }

    public function setNama($nama)
    {
        $this->nama = $nama;
    }

    public function getNim()
    {
        return $this->nim;
    }

    public function setNim($nim)
    {
        $this->nim = $nim;
    }

    public function getUmur()
    {
        return $this->umur;
    }

    public function setUmur($umur)
    {
        $this->umur = $umur;
    }

    // menampilkan informasi mahasiswa
    public function tampilkanInfo()
    {
        echo "Nama: " . $this->nama . PHP_EOL;
        echo "NIM: " . $this->nim . PHP_EOL;
        echo "Umur: " . $this->umur . PHP_EOL;
    }
}
