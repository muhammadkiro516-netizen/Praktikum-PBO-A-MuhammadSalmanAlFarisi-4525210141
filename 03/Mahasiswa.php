<?php

// Parent class
class Mahasiswa
{
    // protected supaya bisa dipakai juga oleh class turunan
    protected $nama;
    protected $nim;
    protected $umur;

    // constructor overloading ala Java digabung jadi satu pakai nilai default
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
