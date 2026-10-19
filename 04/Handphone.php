<?php

class Handphone
{
    protected $merk;
    protected $model;

    public function __construct($merk, $model)
    {
        $this->merk = $merk;
        $this->model = $model;
    }

    public function nyalakan()
    {
        echo "Handphone dinyalakan." . PHP_EOL;
    }

    public function matikan()
    {
        echo "Handphone dimatikan." . PHP_EOL;
    }

    public function telepon($nomor)
    {
        echo "Memanggil nomor " . $nomor . PHP_EOL;
    }
}
